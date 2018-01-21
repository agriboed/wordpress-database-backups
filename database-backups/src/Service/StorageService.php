<?php

namespace DatabaseBackups\Service;

use DatabaseBackups\Interfaces\ObjectInterface;
use DatabaseBackups\Interfaces\StorageInterface;
use DatabaseBackups\Core\AbstractService;
use DatabaseBackups\Storage\Filesystem;

class StorageService extends AbstractService {

	/**
	 * @var array
	 */
	protected $storages = array(
		Filesystem::class,
		AmazonS3::class
	);

	/**
	 * @var array StorageInterface[]
	 */
	protected $connected = array();

	/**
	 * @var array
	 */
	protected $objects = array();

	/**
	 *
	 */
	public function connect() {
		foreach ( $this->storages as $storage ) {
			if ( ! $storage instanceof StorageInterface ) {
				continue;
			}

			if (true === $storage->connect()) {
				$this->connected[] = $storage;
			}
		}
	}

	/**
	 * @param ObjectInterface $object
	 */
	public function putObject(ObjectInterface $object)
	{
		/**
		 * @var $storage StorageInterface
		 */
		foreach ( $this->connected as $storage ) {
			$storage->putObject($object);
		}
	}

	/**
	 * @param $key
	 */
	public function deleteObject($key)
	{
		foreach ($this->connected as $storage) {
			$storage->deleteObject($key);
		}
	}

	/**
	 *
	 * @return array
	 */
	public function getObjectsList()
	{
		/**
		 * @var $storage StorageInterface
		 */
		foreach ($this->connected as $storage)
		{
			$this->objects[$storage->getName()] = $storage->getObjectsList();
		}

		return $this->objects;
	}
}