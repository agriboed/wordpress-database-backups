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
     *
     * @throws \InvalidArgumentException
     */
    protected function connect()
    {
        $this->client = new S3Client([
            'version' => 'latest',
            'region' => OptionsService::getOption('amazon_s3_region'),
            'credentials' => [
                'key' => OptionsService::getOption('amazon_s3_key'),
                'secret' => OptionsService::getOption('amazon_s3_secret'),
            ]
        ]);

        $this->bucket = OptionsService::getOption('amazon_s3_bucket');
    }


    public function get($key) {
        return $this->client->getObject(array(
            'Bucket' => $this->bucket,
            'Key'    => $key
        ));

    }

    /**
     * @param $key
     */
    public function set($key)
    {
        try {
            $this->client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
                'Body' => fopen(__DIR__ . '/readme.txt', 'rb'),
                'ACL' => 'private',
            ]);
        } catch (S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
    }

}