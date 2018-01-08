<?php

namespace DatabaseBackups;

use DatabaseBackups\Interfaces\DependencyInterface;
use DatabaseBackups\Interfaces\HooksInterface;
use DatabaseBackups\Core\Container;

class Bootstrap {
	/**
	 * @var string
	 */
	protected static $key = 'database-backups';

	/**
	 * @var string
	 */
	protected static $version = '1.3.0';

	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * Objects will be started automatically while loading
	 *
	 * @var array
	 */
	protected $autostart = [
		\DatabaseBackups\Controller\AdminController::class,
		\DatabaseBackups\Service\CronService::class,
		\DatabaseBackups\Controller\AjaxController::class
	];

	/**
	 * Bootstrap constructor.
	 *
	 * @param string $plugin
	 */
	public function __construct( $plugin ) {
		$this->container = new Container( $plugin, static::$key, static::$version );

		foreach ( $this->autostart as $class ) {
			$object = new $class;

			if ( $object instanceof DependencyInterface ) {
				$object->setContainer( $this->container );
			}

			if ( $object instanceof HooksInterface ) {
				$object->initHooks();
			}
		}
	}
}