<?php

namespace DatabaseBackups\Controller;

use DatabaseBackups\Core\AbstractController;
use DatabaseBackups\Service\OptionsService;
use DatabaseBackups\Service\BackupService;
use DatabaseBackups\Core\Container;
use DatabaseBackups\Service\S3Service;

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
        if (empty($_POST['options']) || !is_array($_POST['options'])) {
            return $this->response(false, __('Nothing to save', Container::key()));
        }

        /**
         * @var $optionsService OptionsService
         */
        $optionsService = $this->container->get(OptionsService::class);
        $optionsService->setOptions($_POST['options']);

        if (!empty($_POST['amazon_s3']) && 'true' === $_POST['amazon_s3']) {
            /**
             * @var $s3Service S3Service
             */
            $s3Service = $this->container->get(S3Service::class);

            return $s3Service->isConnected() ?
                $this->response(true, __('Options saved. Amazon S3 connection is successful.', Container::key())) :
                $this->response(false, __('Options saved. Amazon S3 connection failed', Container::key()));
        }

        return $this->response(true, __('Options saved', Container::key()));
    }

    /**
     * Method: POST
     *
     * @throws \Exception
     */
    public function createBackup()
    {
        if (!isset($_POST['nonce']) || !$this->checkNonce($_POST['nonce'])) {
            return $this->response();
        }

        /**
         * @var $backupService BackupService
         */
        $backupService = $this->container->get(BackupService::class);
        //$backupService->createBackup();
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