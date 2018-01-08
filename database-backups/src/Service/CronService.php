<?php

namespace DatabaseBackups\Service;

use DatabaseBackups\Interfaces\HooksInterface;
use DatabaseBackups\Core\AbstractService;
use DatabaseBackups\Core\Container;

class CronService extends AbstractService implements HooksInterface {
	/**
	 *
	 */
	public function initHooks() {
		add_action( 'init', [ $this, 'cronCheck' ] );
		add_filter( 'cron_schedules', [ $this, 'cronSchedules' ] );
	}

	/**
	 * @return void
	 */
	public function cronCheck() {
		try {
			/**
			 * @var $backupService BackupService
			 */
			$backupService = $this->container->get( BackupService::class );
			$backupService->checkOldCopies();
		} catch ( \Exception $e ) {
		}
	}

	/**
	 * Adds new schedules to WP
	 *
	 * @param array $schedules
	 *
	 * @return array
	 */
	public function cronSchedules( array $schedules ) {
		$schedules['weekly']        = [
			'interval' => 60 * 60 * 24 * 7,
			'display'  => __( 'Once Weekly', Container::key() )
		];
		$schedules['weekly_twice']  = [
			'interval' => round( ( 60 * 60 * 24 * 7 ) / 2 ),
			'display'  => __( 'Twice Weekly', Container::key() )
		];
		$schedules['monthly']       = [
			'interval' => 60 * 60 * 24 * 7 * 31,
			'display'  => __( 'Once Monthly', Container::key() )
		];
		$schedules['monthly_twice'] = [
			'interval' => round( ( 60 * 60 * 24 * 7 * 31 ) / 2 ),
			'display'  => __( 'Twice Monthly', Container::key() )
		];

		return $schedules;
	}

	/**
	 *
	 */
	public function clearSchedule() {
		wp_clear_scheduled_hook( Container::key() . '-cron' );
	}
}