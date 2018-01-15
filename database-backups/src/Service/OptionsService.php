<?php

namespace DatabaseBackups\Service;

use DatabaseBackups\Core\AbstractService;
use DatabaseBackups\Core\Container;

/**
 * Class Option
 * @package DatabaseBackups\Service
 */
class OptionsService extends AbstractService {
	/**
	 * Returns option
	 *
	 * @param string $option_name
	 * @param null $default
	 *
	 * @return mixed
	 */
	public static function getOption( $option_name, $default = null ) {
		return get_option( Container::key() . '_' . $option_name, $default );
	}

	/**
	 * Set option
	 *
	 * @param $option_name
	 * @param $value
	 *
	 * @return bool
	 */
	protected function setOption( $option_name, $value ) {
		return update_option( Container::key() . '_' . $option_name, $value );
	}

	/**
	 * Set whole options
	 *
	 * @param array $options
	 */
	public function setOptions( array $options ) {
		if ( isset( $options['directory'] ) && ! empty( $options['directory'] ) ) {
			$this->setOption( 'directory', sanitize_text_field( $options['directory'] ) );
		}

		$this->setOption( 'limit', isset( $options['limit'] ) ? (int) $options['limit'] : 0 );
		$this->setOption( 'prefix', isset( $options['prefix'] ) ? true : false );
		$this->setOption( 'clean', isset( $options['clean'] ) ? true : false );
		$this->setOption( 'notify', isset( $options['notify'] ) ? true : false );
		$this->setOption( 'gzip', isset( $options['gzip'] ) ? true : false );
		$this->setOption( 'utf8', isset( $options['utf8'] ) ? true : false );
		$this->setOption( 'cron', isset( $options['cron'] ) ? true : false );
		$this->setOption( 'cron_frequency',
			! empty( $options['cron_frequency'] ) ? sanitize_text_field( $options['cron_frequency'] ) : null );
		$this->setOption( 'delete', isset( $options['delete'] ) ? true : false );
		$this->setOption( 'delete_days', isset( $options['delete_days'] ) ? (int) $options['delete_days'] : 0 );
		$this->setOption( 'delete_copies', isset( $options['delete_copies'] ) ? (int) $options['delete_copies'] : 0 );
		$this->setOption( 'amazon_s3', isset( $options['amazon_s3'] ) ? true : false );
		$this->setOption( 'amazon_s3_region',
			isset( $options['amazon_s3_region'] ) ? sanitize_text_field( $options['amazon_s3_region'] ) : null );
		$this->setOption( 'amazon_s3_bucket',
			isset( $options['amazon_s3_bucket'] ) ? sanitize_text_field( $options['amazon_s3_bucket'] ) : null );
		$this->setOption( 'amazon_s3_key',
			isset( $options['amazon_s3_key'] ) ? sanitize_text_field( $options['amazon_s3_key'] ) : null );
		$this->setOption( 'amazon_s3_secret',
			isset( $options['amazon_s3_secret'] ) ? sanitize_text_field( $options['amazon_s3_secret'] ) : null );

		$this->validateOptions();

		if ( isset( $options['cron'] ) ) {
			CronService::createTask();
		} else {
			CronService::clearSchedule();
		}
	}

	/**
	 *
	 */
	public function validateOptions() {
		if ( static::getOption( 'delete_copies' ) === 0 && static::getOption( 'delete_days' ) === 0 ) {
			$this->setOption( 'delete', 0 );
		}

		if ( empty( static::getOption( 'amazon_s3_bucket' ) ) ||
		     empty( static::getOption( 'amazon_s3_key' ) ) ||
		     empty( static::getOption( 'amazon_s3_secret' ) )
		) {
			$this->setOption( 'amazon_s3', false );
		}
	}
}