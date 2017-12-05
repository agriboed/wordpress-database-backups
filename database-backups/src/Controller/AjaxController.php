<?php

namespace DatabaseBackups\Controller;

use DatabaseBackups\Core\AbstractController;
use DatabaseBackups\Service\BackupService;
use DatabaseBackups\Service\OptionsService;
use DatabaseBackups\Core\Container;

/**
 * Class AjaxController
 * @package DatabaseBackups\Controller
 */
class AjaxController extends AbstractController
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     *
     */
    public function initHooks()
    {
        add_action('wp_ajax_' . Container::key() . '_options', [$this, 'saveOptions']);
        add_action('wp_ajax_' . Container::key() . '_create', [$this, 'createBackup']);
        add_action('wp_ajax_' . Container::key() . '_deletes', [$this, 'deleteBackup']);
        add_action('wp_ajax_' . Container::key() . '_amazon_s3', [$this, 'checkAmazonS3']);
    }

    /**
     * @param $nonce
     * @return bool
     */
    protected function checkNonce($nonce)
    {
        return wp_create_nonce(Container::key()) === $nonce;
    }

    /**
     * Method: POST
     *
     * @throws \Exception
     */
    public function saveOptions()
    {
        if (!isset($_POST['options']) || !is_array($_POST['options'])) {
            return $this->response();
        }

        /**
         * @var $optionsService OptionsService
         */
        $optionsService = $this->container->get(OptionsService::class);
        $optionsService->setOptions($_POST['options']);

        return $this->response(true);
    }

    /**
     * Method: POST
     * @throws \Exception
     */
    public function createBackup()
    {
        if (!isset($_POST['do_backup_manually']) || $_POST['do_backup_manually'] !== 1) {
            return $this->response();
        }

        /**
         * @var $backupService BackupService
         */
        $backupService = $this->container->get(BackupService::class);
        $backupService->createBackup();
        return $this->response(true);
    }

    /**
     * Method: POST
     *
     * @throws \Exception
     */
    public function deleteBackup()
    {
        if (!isset($_POST['delete']) || 0 === (int)$_POST['delete']) {
            return $this->response();
        }

        /**
         * @var $backupService BackupService
         */
        $backupService = $this->container->get(BackupService::class);
        $backupService->deleteBackup($_POST['delete']);

        return $this->response(true);
    }

    /**
     *
     */
    public function checkAmazonS3()
    {

    }

    /**
     * @param bool $success
     * @param string $message
     */
    protected function response($success = false, $message = '')
    {
        $this->data['success'] = $success;
        $this->data['message'] = $message;

        echo json_encode($this->data);
        wp_die();
    }
}