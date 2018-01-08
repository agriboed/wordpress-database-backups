<?php

namespace DatabaseBackups\Controller;

use DatabaseBackups\Core\AbstractController;
use DatabaseBackups\Service\OptionsService;
use DatabaseBackups\Service\BackupService;
use DatabaseBackups\Exceptions\Exception;
use DatabaseBackups\Model\BackupModel;
use DatabaseBackups\Core\Container;

/**
 * Class Admin
 * @package DatabaseBackups\Controller
 */
class AdminController extends AbstractController {
	/**
	 * Init WP hooks
	 */
	public function initHooks() {
		add_action( 'admin_menu', [ $this, 'adminMenu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'registerAssets' ] );
		add_filter( 'plugin_action_links_' . Container::basename(), [ $this, 'pluginActionLinks' ], 10, 2 );
		load_plugin_textdomain( Container::key(), '', '/database-backups/' .
		                                              'languages' );
	}

	/**
	 *
	 */
	public function registerAssets() {
		wp_register_script( Container::key() . '-admin', Container::pluginUrl() . 'assets/js/app.js', [],
			Container::version() );
		wp_register_style( Container::key() . '-admin', Container::pluginUrl() . 'assets/css/admin.css', [],
			Container::version() );
	}

	/**
	 * Add links to admin menu
	 */
	public function adminMenu() {
		add_submenu_page( 'tools.php', __( 'Database Backups', Container::key() ),
			__( 'Database Backups', Container::key() ),
			'manage_options', Container::key(),
			[ $this, 'renderPage' ] );
	}

	/**
	 * @param $links
	 *
	 * @return array
	 */
	public function pluginActionLinks( $links ) {
		unset( $links['edit'] );

		return array_merge( [
			'<a href="' . admin_url( 'tools.php?page=' . Container::key() ) . '">' . __( 'Settings',
				Container::key() ) . '</a>'
		], $links );
	}

	/**
	 *
	 * @throws Exception
	 */
	public function renderPage() {
		wp_enqueue_script( Container::key() . '-admin' );
		wp_enqueue_style( Container::key() . '-admin' );

		/**
		 * @var $backupService BackupService
		 */
		$backupService = $this->container->get( BackupService::class );
		$backupService->checkOldCopies();

		/**
		 * @var $backupModel BackupModel
		 */
		$backupModel = $this->container->get( BackupModel::class );

		$data = [
			'key'              => Container::key(),
			'backups'          => $backupService->getBackups(),
			'occupied_space'   => $backupService->getOccupiedSpace(),
			'total_free_space' => $backupService->getTotalFreeSpace(),
			'wp_upload_dir'    => wp_upload_dir(),
			'directory'        => OptionsService::getOption( 'directory', 'database-backups' ),
			'limit'            => OptionsService::getOption( 'limit', 0 ),
			'prefix'           => (bool) OptionsService::getOption( 'prefix' ),
			'table'            => DB_NAME,
			'prefix_default'   => $backupModel->getPrefix(),
			'clean'            => (bool) OptionsService::getOption( 'clean' ),
			'notify'           => (bool) OptionsService::getOption( 'notify' ),
			'gzip'             => (bool) OptionsService::getOption( 'gzip' ),
			'gzip_status'      => function_exists( 'gzencode' ),
			'utf8'             => (bool) OptionsService::getOption( 'utf8' ),
			'utf8_status'      => function_exists( 'mb_detect_encoding' ),
			'cron'             => (bool) OptionsService::getOption( 'cron' ),
			'cron_frequency'   => OptionsService::getOption( 'cron_frequency' ),
			'schedules'        => wp_get_schedules(),
			'delete'           => (bool) OptionsService::getOption( 'delete' ),
			'delete_days'      => OptionsService::getOption( 'delete_days', 0 ),
			'delete_copies'    => OptionsService::getOption( 'delete_copies', 0 ),
			'amazon_s3'        => (bool) OptionsService::getOption( 'amazon_s3' ),
			'amazon_s3_region' => OptionsService::getOption( 'amazon_s3_region' ),
			'amazon_s3_bucket' => OptionsService::getOption( 'amazon_s3_bucket' ),
			'amazon_s3_key'    => OptionsService::getOption( 'amazon_s3_key' ),
			'amazon_s3_secret' => OptionsService::getOption( 'amazon_s3_secret' ),
			'i'                => 1,
			'nonce'            => wp_create_nonce( Container::key() ),
			'admin_url'        => admin_url( 'admin-ajax.php' )
		];

		if ( ! file_exists( Container::pluginDir() . 'templates/admin.php' ) ) {
			throw new Exception( 'Template not found' );
		}

		include Container::pluginDir() . 'templates/admin.php';
	}
}