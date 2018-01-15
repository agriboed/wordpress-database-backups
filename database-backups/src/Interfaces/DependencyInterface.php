<?php

namespace DatabaseBackups\Interfaces;

use DatabaseBackups\Core\Container;

/**
 * Interface DependencyInterface
 * @package DatabaseBackups\Interfaces
 */
interface DependencyInterface {

	/**
	 * @param Container $container
	 *
	 * @return mixed
	 */
	public function setContainer( Container $container );
}