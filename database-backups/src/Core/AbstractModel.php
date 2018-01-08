<?php

namespace DatabaseBackups\Core;

use DatabaseBackups\Interfaces\DependencyInterface;

class AbstractModel implements DependencyInterface {
	/**
	 * @var \PDO
	 */
	protected $db;

	/**
	 * @var \wpdb
	 */
	protected $wpdb;

	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * AbstractModel constructor.
	 */
	public function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;
		$this->db   = new \PDO( 'mysql:host=' . DB_HOST . '; dbname=' . DB_NAME, DB_USER, DB_PASSWORD );
		$this->db->exec( 'SET NAMES "' . DB_CHARSET . '"' );
	}

	/**
	 * @return string
	 */
	public function getPrefix() {
		return $this->wpdb->prefix;
	}

	/**
	 * @param Container $container
	 */
	public function setContainer( Container $container ) {
		$this->container = $container;
	}
}