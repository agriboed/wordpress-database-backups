<?php

namespace DatabaseBackups\Core;

use DatabaseBackups\Interfaces\DependencyInterface;
use DatabaseBackups\Exceptions\Exception;

/**
 * Class Container
 * @package Openwork\DependencyInjection
 */
class Container {

	/**
	 * @var array
	 *
	 */
	protected $dependencies;

	/**
	 * @var string
	 */
	protected static $key;

	/**
	 * @var string
	 */
	protected static $plugin_dir;

	/**
	 * @var string
	 */
	protected static $basename;

	/**
	 * @var string
	 */
	protected static $plugin_url;

	/**
	 * @var string
	 */
	protected static $version;

	/**
	 * Container constructor.
	 *
	 * @param $plugin
	 * @param $key
	 * @param string $version
	 */
	public function __construct( $plugin, $key, $version = '' ) {
		static::$key        = $key;
		static::$version    = $version;
		static::$basename   = plugin_basename( $plugin );
		static::$plugin_dir = plugin_dir_path( $plugin );
		static::$plugin_url = plugin_dir_url( $plugin );
	}

	/**
	 * @return null|string
	 */
	public static function key() {
		return static::$key;
	}

	/**
	 * @return string
	 */
	public static function version() {
		return static::$version;
	}

	/**
	 * @return string
	 */
	public static function basename() {
		return static::$basename;
	}

	/**
	 * @return string
	 */
	public static function pluginUrl() {
		return static::$plugin_url;
	}

	/**
	 * @return mixed
	 */
	public static function pluginDir() {
		return static::$plugin_dir;
	}

	/**
	 * @param $dependency
	 *
	 * @return self
	 */
	public function set( $dependency ) {
		$this->dependencies[ get_class( $dependency ) ] = $dependency;

		return $this;
	}

	/**
	 * Return from memory or create and put to memory new object
	 *
	 * @param string $dependency
	 * @param array $arguments
	 *
	 * @throws \ReflectionException
	 * @throws Exception
	 *
	 * @return mixed
	 */
	public function get( $dependency, array $arguments = [] ) {
		if ( ! isset( $this->dependencies[ $dependency ] ) ) {
			if ( ! class_exists( $dependency ) ) {
				throw new Exception( 'Dependency not found' );
			}

			if ( empty( $arguments ) ) {
				$instance = new $dependency;
			} else {
				$reflect  = new \ReflectionClass( $dependency );
				$instance = $reflect->newInstanceArgs( $arguments );
			}

			if ( $instance instanceof DependencyInterface ) {
				$instance->setContainer( $this );
			}

			$this->dependencies[ $dependency ] = $instance;
		}

		return $this->dependencies[ $dependency ];
	}
}