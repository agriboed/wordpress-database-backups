<?php

namespace DatabaseBackups\Service;

use Aws\Exception\AwsException;
use DatabaseBackups\Core\AbstractService;
use DatabaseBackups\Exceptions\Exception;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

class S3Service extends AbstractService {
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
	 * S3Service constructor.
	 * @throws \InvalidArgumentException
	 */
	public function __construct() {
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
			$this->client->listObjects( [
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
	public function get( $key ) {
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
	public function delete( $key ) {
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
	public function set( $key, $data ) {
		if ( false === $this->isConnected() ) {
			return false;
		}

		try {
			$this->client->putObject( [
				'Region' => $this->region,
				'Bucket' => $this->bucket,
				'Key'    => $key,
				'Body'   => $data,
				'ACL'    => 'private',
			] );

		} catch ( S3Exception $e ) {
			return false;
		}

		return true;
	}
}