<?php

namespace DatabaseBackups\Service;

use DatabaseBackups\Core\AbstractService;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

class S3Service extends AbstractService
{
    /**
     * @var $client S3Client
     */
    protected $client;

    /**
     * @var
     */
    protected $bucket;

    /**
     * S3Service constructor.
     */
    public function __construct()
    {
        $this->bucket = OptionsService::getOption('amazon_s3_bucket');

        if (empty($this->bucket)) {
            return;
        }

        try {
            $this->client = new S3Client([
                'version' => 'latest',
                'region' => OptionsService::getOption('amazon_s3_region'),
                'credentials' => [
                    'key' => OptionsService::getOption('amazon_s3_key'),
                    'secret' => OptionsService::getOption('amazon_s3_secret'),
                ]
            ]);

            //try to check connection
            $result = $this->client->listBuckets();
            $bucketIsset = false;

            if (is_array($result['Buckets'])) {
                foreach ($result['Buckets'] as $bucket) {
                    if ($bucket['Name'] === $this->bucket) {
                        $bucketIsset = true;
                    }
                }
            }

            if (false === $bucketIsset) {
                $this->client = null;
            }
        } catch (\Exception $exception) {
            $this->client = null;
        }
    }

    /**
     *
     * @throws \InvalidArgumentException
     */
    public function isConnected()
    {
        return ($this->client instanceof S3Client);
    }

    /**
     * Returns object form storage
     *
     * @param $key
     * @return \Aws\Result|null
     * @throws \InvalidArgumentException
     */
    public function get($key)
    {
        if (false === $this->isConnected()) {
            return null;
        }

        return $this->client->getObject(array(
            'Bucket' => $this->bucket,
            'Key' => $key
        ));

    }

    /**
     * @param $key
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function set($key)
    {
        if (false === $this->isConnected()) {
            return false;
        }

        try {
            $this->client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
                'Body' => fopen(__DIR__ . '/readme.txt', 'rb'),
                'ACL' => 'private',
            ]);
        } catch (S3Exception $e) {
            echo "There was an error uploading the file.\n";

            return false;
        }

        return true;
    }

}