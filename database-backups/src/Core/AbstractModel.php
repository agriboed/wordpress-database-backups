<?php

namespace DatabaseBackups\Core;

use DatabaseBackups\Interfaces\DependencyInterface;

class AbstractModel implements DependencyInterface
{
    /**
     * @var \wpdb
     */
    protected $db;

    /**
     * @var Container
     */
    protected $container;

    /**
     * AbstractModel constructor.
     */
    public function __construct()
    {
        global $wpdb;

        $this->db = $wpdb;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->db->prefix;
    }

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}