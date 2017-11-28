<?php

namespace DatabaseBackups\Controller;


use DatabaseBackups\Core\Container;
use DatabaseBackups\Interfaces\HooksInterface;

class Admin extends ControllerAbstract implements HooksInterface
{
    /**
     * Init WP hooks
     */
    public function initHooks()
    {
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'addMenuLink'));
        add_filter('plugin_action_links_' . Container::basename(), array($this, 'modifyPluginLinks'), 10, 2);
    }

    /**
     *
     */
    public function addMenuLink()
    {
        add_submenu_page('tools.php', __('Database Backups', Container::key()), __('Database Backups', Container::key()),
            'manage_options', 'database-backups',
            array($this, 'renderPage'));
    }

    /**
     * @param $links
     * @return array
     */
    public function modifyPluginLinks($links)
    {
        unset($links['edit']);

        return array_merge(array('<a href="' . admin_url('tools.php?page=' . Container::key()) . '">' . __('Settings', Container::key()) . '</a>'), $links);

    }

    /**
     *
     */
    public function renderPage()
    {
        if (isset($_POST['do_backup_manually']) && $_POST['do_backup_manually'] == 1) {
            if (Core::instance()->doBackup())
                echo '<div id="message" class="updated notice notice-success is-dismissible"><p>' .
                    __('Backup Created', 'database-backups') .
                    '</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' .
                    _('Close') .
                    '</span></button></div>';
        }

        $saved = false;

        if (isset($_POST['options'])) {
            $this->setOptions($_POST['options']);
            echo '<div id="message" class="updated notice notice-success is-dismissible"><p>' .
                __('Settings Saved', 'database-backups') .
                '</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' .
                _('Close') .
                '</span></button></div>';
            $saved = true;
        }

        if (isset($_GET['delete']) && !empty($_GET['delete']))
            Core::instance()->deleteBackup($_GET['delete']);

        Core::instance()->checkOldBackups();
        $backups = Core::instance()->getBackups();
        $gzip_status = !function_exists('gzencode') ? 'disabled' : '';
        $utf8_status = !function_exists('mb_detect_encoding') ? 'disabled' : '';
        $wp_upload_dir = wp_upload_dir();
        $directory = self::getOption('directory');
        $limit = self::getOption('limit');
        $prefix = self::getOption('prefix') ? 'checked' : '';
        $clean = self::getOption('clean') ? 'checked' : '';
        $notify = self::getOption('notify') ? 'checked' : '';
        $gzip = self::getOption('gzip') ? 'checked' : '';
        $utf8 = self::getOption('utf8') ? 'checked' : '';
        $cronOption = self::getOption('cron');
        $delete = self::getOption('delete') ? 'checked' : '';
        $delete_days = self::getOption('delete_days');
        $delete_copies = self::getOption('delete_copies');
        $occupied_space = round(Core::instance()->occupiedSpace($backups) / 1024 / 1024, 2);
        $total_free_space = round(disk_free_space($_SERVER['DOCUMENT_ROOT']) / 1024 / 1024, 2);

        include Container::pluginDir() . '/templates/admin.php';
    }
}