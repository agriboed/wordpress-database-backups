<?php

namespace DatabaseBackups\Controller;

use DatabaseBackups\Core\AbstractController;
use DatabaseBackups\Service\OptionsService;
use DatabaseBackups\Service\BackupService;
use DatabaseBackups\Service\S3Service;
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
    protected $data;

    /**
     *
     */
    public function initHooks()
    {
        add_action('wp_ajax_' . Container::key() . '_options', [$this, 'saveOptions']);
        add_action('wp_ajax_' . Container::key() . '_create', [$this, 'createBackup']);
        add_action('wp_ajax_' . Container::key() . '_delete', [$this, 'deleteBackup']);
    }

    /**
     * Check WP Nonce field
     *
     * @param bool $nonce
     * @return bool
     */
    protected function checkNonce($nonce = false)
    {
        if (false === $nonce && empty($_POST['nonce'])) {
            $this->data['success'] = false;
            $this->data['message'] = __('Security error', Container::key());

            return false;
        }

        $nonce = ($nonce !== false) ? $nonce : $_POST['nonce'];

        if (wp_create_nonce(Container::key()) !== $nonce) {
            $this->data['success'] = false;
            $this->data['message'] = __('Security error', Container::key());
            return false;
        }

        return true;
    }

    /**
     * Method: POST
     *
     * @throws \DatabaseBackups\Exceptions\Exception
     * @throws \InvalidArgumentException
     */
    public function saveOptions()
    {
        if (false === $this->checkNonce()) {
            $this->data['success'] = false;
            $this->data['message'] = __('Security error', Container::key());
            return $this->response();
        }

        if (empty($_POST['options']) || !is_array($_POST['options'])) {
            $this->data['success'] = false;
            $this->data['message'] = __('Nothing to save', Container::key());
            return $this->response();
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

            if ($s3Service->isConnected()) {
                $this->data['success'] = true;
                $this->data['message'] = __('Options saved. Amazon S3 connection is successful.', Container::key());
            } else {
                $this->data['success'] = false;
                $this->data['message'] = __('Options saved. Amazon S3 connection failed', Container::key());
            }
        }

        return $this->response();
    }

    /**
     * Method: POST
     *
     * @throws \Exception
     */
    public function createBackup()
    {
        if (false === $this->checkNonce()) {
            return $this->response();
        }

        /**
         * @var $backupService BackupService
         */
        $backupService = $this->container->get(BackupService::class);
        $result = $backupService->createBackup();

        if (false === $result) {
            $this->data['success'] = false;
            $this->data['message'] = __('Backup not created', Container::key());

            return $this->response();
        }

        $this->data['success'] = true;
        $this->data['message'] = __('Backup created successful', Container::key());
        $this->data['backup'] = $backupService->getBackup($result);

        return $this->response();
    }

    /**
     * Method: POST
     *
     * @throws \Exception
     */
    public function deleteBackup()
    {
        if (false === $this->checkNonce()) {
            return $this->response();
        }

        if (empty($_POST['backup'])) {
            $this->data['success'] = false;
            $this->data['message'] = 'Nothing to delete';
            return $this->response();
        }

        /**
         * @var $backupService BackupService
         */
        $backupService = $this->container->get(BackupService::class);
        $result = $backupService->deleteBackup($_POST['backup']);

        if (true === $result) {
            $this->data['success'] = true;
            $this->data['message'] = __('Backup deleted', Container::key());
        } else {
            $this->data['success'] = false;
            $this->data['message'] = __('Backup not deleted', Container::key());
        }

        return $this->response();
    }

    /**
     *
     */
    protected function response()
    {
        echo json_encode($this->data);
        wp_die();
    }
}