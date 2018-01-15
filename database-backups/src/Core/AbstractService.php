<?php

namespace DatabaseBackups\Core;

use DatabaseBackups\Interfaces\DependencyInterface;

class AbstractService implements DependencyInterface {

	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * @param Container $container
	 *
	 * @return void
	 */
	public function setContainer( Container $container ) {
		$this->container = $container;
	}
}