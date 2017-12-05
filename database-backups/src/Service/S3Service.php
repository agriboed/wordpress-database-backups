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
    protected $region;

    /**
     * @var
     */
    protected $key;

    /**
     * @var
     */
    protected $secret;

    /**
     *
     * @throws \InvalidArgumentException
     */
    protected function connect()
    {
        $this->client = new S3Client([
            'version' => 'latest',
            'region' => 'us-east-2',
            'credentials' => [
                'key' => 'AKIAIH67YVIVSG6YYG4A',
                'secret' => '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',
            ]
        ]);
    }

    /**
     * @param $key
     */
    public function get($key) {

    }

    /**
     * @param $key
     */
    public function set($key)
    {
        try {
            $this->client->putObject([
                'Bucket' => 'agriboed-database-backups',
                'Key' => $key,
                'Body' => fopen(__DIR__ . '/readme.txt', 'rb'),
                'ACL' => 'private',
            ]);
        } catch (S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
    }

}