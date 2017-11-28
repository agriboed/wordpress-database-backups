<div class="wrap"><h1><?php _e('Database Backups'); ?></h1>
    <h2><a href="#" class="settings-toggle"><?php _e('Settings'); ?></a></h2>
    <form action="<?php echo PLUGIN_LINK; ?>" method="post" class="settings-wrap">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><?php _e('Directory name', 'database-backups'); ?></th>
                <td>
                    <?php echo $wp_upload_dir['baseurl']; ?>/<input type="text" name="options[directory]"
                                                                    placeholder="database-backups"
                                                                    value="<?php echo (!empty($directory)) ? $directory : 'database-backups'; ?>"
                                                                    required>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Use limits', 'database-backups'); ?></th>
                <td>
                    <input type="number" min="0" max="10000" name="options[limit]"
                           placeholder="0"
                           value="<?php echo $limit; ?>">
                    <p class="description"><?php _e('Set round value if you see PHP memory error. 0 - don\'t use limits', 'database-backups'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Use prefix', 'database-backups'); ?></th>
                <td>
                    <input type="checkbox" name="options[prefix]" <?php echo $prefix; ?>>
                    <p class="description"><?php _e('Backup only WP tables with prefix', 'database-backups'); ?>
                        "<?php echo self::getTablePrefix(); ?>"</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Clean database', 'database-backups'); ?></th>
                <td>
                    <input type="checkbox" name="options[clean]" <?php echo $clean; ?>>
                    <p class="description"><?php _e('Don\'t save in backup file: posts/page revisions, posts/page in trash, spam comments, comments in trash', 'database-backups'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Notification to admin', 'database-backups'); ?></th>
                <td>
                    <input type="checkbox" name="options[notify]" <?php echo $notify; ?>>
                    <p class="description"><?php _e('Send Email to admin with result of backup operation', 'database-backups'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Enable GZip compression', 'database-backups'); ?></th>
                <td>
                    <input type="checkbox" name="options[gzip]" <?php echo $gzip . ' ' . $gzip_status; ?>>
                    <p class="description"><?php _e('Allow to pack your backup in GZ archive', 'database-backups'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Enable converting to UTF-8', 'database-backups'); ?></th>
                <td>
                    <input type="checkbox" name="options[utf8]" <?php echo $utf8 . ' ' . $utf8_status; ?>>
                    <p class="description"><?php _e('You can check this option if have problems with encoding when import database', 'database-backups'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Enable auto backups', 'database-backups'); ?></th>
                <td>
                    <select name="options[cron]">
                        <option value="" <?php echo empty($cronOption) ? 'selected' : ''; ?>>
                            <?php _e('Disabled', 'database-backups'); ?>
                        </option>
                        <?php foreach (wp_get_schedules() as $cron=>$value): ?>
                            <option value="<?php echo $cron; ?>" <?php echo ($cronOption ==
                                $cron) ? 'selected' : ''; ?>><?php echo $value['display'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Enable auto delete', 'database-backups'); ?></th>
                <td>
                    <input type="checkbox" class="auto-delete-checkbox"
                           name="options[delete]" <?php echo $delete; ?>>
                    <p class="description"><?php _e('Check this option if you want auto deleting the old backups', 'database-backups'); ?></p>
                </td>
            </tr>
            <tr class="auto-delete">
                <th scope="row"><?php _e('Delete after * days', 'database-backups'); ?></th>
                <td>
                    <input type="number" name="options[delete_days]" value="<?php echo $delete_days ?>" min="0"
                           max="1000">
                    <p class="description"><?php _e('How many days to store the backups. 0 - for disable this option', 'database-backups'); ?></p>
                </td>
            </tr>
            <tr class="auto-delete">
                <th scope="row"><?php _e('Delete after * copies', 'database-backups'); ?></th>
                <td>
                    <input type="number" name="options[delete_copies]" value="<?php echo $delete_copies ?>"
                           min="0" max="1000">
                    <p class="description"><?php _e('How many copies of backups to store. 0 - for disable this option', 'database-backups'); ?></p>
                </td>
            </tr>
            </tbody>
        </table>
        <?php
        submit_button(null, 'primary', null, null, array('autocomplete'=>'off'));
        ?></form>
    <div class="wrap">
        <h2><?php _e('All backups', 'database-backups'); ?></h2>
        <?php
        if (count($backups) === 0)
            echo "<div class='no-backups'><p>" . __('No backups yet', 'database-backups') . "</p></div>";
        else {
            $i=1;
            echo "<table class='wp-list-table widefat striped'><thead><tr><th>" .
                __('ID', 'database-backups') .
                "</th><th>" .
                __('File Name', 'database-backups') .
                "</th><th>" .
                __('Size', 'database-backups') .
                "</th><th>" .
                __('Date', 'database-backups') .
                "</th><th>" .
                __('Actions', 'database-backups') .
                "</th></tr></thead>";
            foreach ($backups as $backup) {
                $icon='';
                if ($backup['format'] == 'sql')
                    $icon='dashicons-media-spreadsheet';
                elseif ($backup['format'] == 'gz')
                    $icon='dashicons-portfolio';
                echo "<tr><td>" .
                    $i++ .
                    "<td><span class='icon dashicons " . $icon . "'></span>" . $backup['name'] .
                    "</td><td>" .
                    round($backup['size'] / 1024 / 1024, 2) .
                    " MB</td><td>" .
                    date_i18n('j M Y H:i', $backup['date']) .
                    "</td><td><a href='" .
                    $backup['url'] .
                    "' class='button'><span class='icon dashicons dashicons-download'></span>" .
                    __('Download', 'database-backups') .
                    "</a>
                            <a href='" . PLUGIN_LINK . "&delete=" .
                    $backup['name'] .
                    "' class='button'><span class='icon dashicons dashicons-trash'></span>" .
                    __('Delete', 'database-backups') .
                    "</a>
                            </td></tr>";
            }
            echo "<tfoot><tr><th>" .
                ($i - 1) .
                "</th><th></th><th>" .
                $occupied_space .
                " MB / " .
                $total_free_space .
                " MB</th><th></th><th></th></tr></tfoot></table>";
        }
        ?>
    </div>
    <form action="<?php echo PLUGIN_LINK; ?>" method="post">
        <p></p>
        <input type="hidden" name="do_backup_manually" value="1">
        <?php
        submit_button(__('Do backup', 'database-backups'), 'primary', null, null, array('autocomplete'=>'off'));
        ?>
    </form>
</div>
<style>
    .settings-toggle {
        text-decoration: none;
        border-bottom: 1px dotted;
        font-weight: 400;
    }

    .settings-toggle:focus {
        box-shadow: none;
    }

    .settings-wrap {
    <?php echo ($saved) ? 'display:block' : 'display:none';?>;
    }

    .settings-wrap .auto-delete {
    <?php echo ($delete) ? 'display:table-row' : 'display:none';?>;
    }

    .wp-list-table span.icon {
        margin: 0 5px 0 0;
    }

    .wp-list-table a span.icon {
        line-height: 1.3em;
    }

    .no-backups {
        margin: 5px 0 15px;
        border-left: 3px solid #ffb900;
        background: #fff;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        padding: 1px 12px;
    }
</style>
<script>
    jQuery('.settings-toggle').click(function () {
        var s = jQuery('.settings-wrap');

        if (s.css('display') == 'block')
            s.fadeOut();
        else
            s.fadeIn(200);
    });

    jQuery('.auto-delete-checkbox').click(function () {
        var c = jQuery(this);
        var a = jQuery('.auto-delete');

        if (c.attr('checked')) {
            a.fadeIn(200);
        } else {
            a.fadeOut(200);
        }
    });
</script>