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
		add_action( Container::key() . '-cron', [ $this, 'processTasks' ] );
		add_filter( 'cron_schedules', [ $this, 'modifyCronSchedules' ] );
	}

	/**
	 * Adds new schedules to WP
	 *
	 * @param array $schedules
	 *
	 * @return array
	 */
	public function modifyCronSchedules( array $schedules ) {
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
	public function processTasks() {
		try {
			/**
			 * @var $backupService BackupService
			 */
			$backupService = $this->container->get( BackupService::class );
			$backupService->createBackup();
			$backupService->checkOldCopies();
		} catch ( \Exception $e ) {
		}
	}

	/**
	 *
	 */
	public static function createTask() {
		if ( false === OptionsService::getOption( 'cron' ) ) {
			return;
		}

		wp_clear_scheduled_hook( Container::key() . '-cron' );

		if ( ! wp_next_scheduled( Container::key() . '-cron' ) ) {
			wp_schedule_event( time(), OptionsService::getOption( 'cron_frequency' ), Container::key() . '-cron' );
		}
	}

	/**
	 *
	 */
	public static function clearSchedule() {
		if ( false === OptionsService::getOption('cron')) {
			wp_clear_scheduled_hook( Container::key() . '-cron' );
		}
	}
}