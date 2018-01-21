<?php

namespace DatabaseBackups\Interfaces;

interface StorageInterface {

	/**
	 * Method connects to remote storage and
	 * returns status of a connection
	 *
	 * @return bool
	 */
	public function connect();

	/**
	 * Method returns name of a current storage
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Method should save an object in a remote storage
	 * and returns status of saving
	 *
	 * @param ObjectInterface $object
	 *
	 * @return bool
	 */
	public function putObject(ObjectInterface $object);

	/**
	 * Method should returns an object or null
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function getObject($key);

	/**
	 * @return ObjectInterface[]
	 */
	public function getObjectsList();

	/**
	 * @param $key
	 *
	 * @return bool
	 */
	public function deleteObject($key);

	/**
	 * @return bool
	 */
	public function isConnected();
}