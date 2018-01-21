<?php

namespace DatabaseBackups\Service;

use DatabaseBackups\Interfaces\ObjectInterface;
use DatabaseBackups\Interfaces\StorageInterface;
use DatabaseBackups\Exceptions\Exception;
use Aws\S3\Exception\S3Exception;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;

class AmazonS3 implements StorageInterface {

	/**
	 * @var string
	 */
	protected static $name = 'Amazon S3';

	/**
	 * @var $client S3Client
	 */
	protected $client;

	/**
	 * @var string
	 */
	protected $bucket;

	/**
	 * @var string
	 */
	protected $region;

	/**
	 * @var array
	 */
	protected static $objects;

	/**
	 *
	 */
	public function connect() {
		$this->bucket = OptionsService::getOption( 'amazon_s3_bucket' );

		if ( empty( $this->bucket ) ) {
			return;
		}

		try {
			$this->client = new S3Client( [
				'version'     => 'latest',
				'region'      => OptionsService::getOption( 'amazon_s3_region' ),
				'credentials' => [
					'key'    => OptionsService::getOption( 'amazon_s3_key' ),
					'secret' => OptionsService::getOption( 'amazon_s3_secret' ),
				],
			] );

			//check region
			static::$objects = $this->client->listObjects( [
				'Bucket' => $this->bucket,
			] );
		} catch ( Exception $exception ) {

			echo $exception->getMessage();
		} catch ( AwsException $exception ) {
			echo $exception->getMessage();
		}
	}


	/**
	 *
	 * @throws \InvalidArgumentException
	 */
	public function isConnected() {
		return ( $this->client instanceof S3Client );
	}

	/**
	 * Returns object form storage
	 *
	 * @param $key
	 *
	 * @return \Aws\Result|null
	 * @throws \InvalidArgumentException
	 */
	public function getObject( $key ) {
		if ( false === $this->isConnected() ) {
			return null;
		}

		return $this->client->getObject( array(
			'Region' => $this->region,
			'Bucket' => $this->bucket,
			'Key'    => $key
		) );
	}

	/**
	 * @param $key
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public function deleteObject( $key ) {
		if ( false === $this->isConnected() ) {
			return false;
		}

		try {
			$this->client->deleteObject( [
				'Region' => $this->region,
				'Bucket' => $this->bucket,
				'Key'    => $key,
			] );

		} catch ( S3Exception $e ) {
			return false;
		}

		return true;
	}

	/**
	 * @param $key
	 * @param $data
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public function putObject( ObjectInterface $object ) {
		if ( false === $this->isConnected() ) {
			return false;
		}

		try {
			$this->client->putObject( [
				'Region' => $this->region,
				'Bucket' => $this->bucket,
				'Key'    => $object->getName(),
				'Body'   => $object->getBody(),
				'ACL'    => 'private',
			] );

		} catch ( S3Exception $e ) {
			return false;
		}

		return true;
	}

	/**
	 *
	 */
	public function getObjectsList()
	{

	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return static::$name;
	}
}