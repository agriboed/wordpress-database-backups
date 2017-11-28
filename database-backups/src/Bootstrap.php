<?php

namespace DatabaseBackups;


use DatabaseBackups\Interfaces\HooksInterface;
use DatabaseBackups\Interfaces\DependencyInterface;
use DatabaseBackups\Core\Container;

class Bootstrap
{
    /**
     * @var string
     */
    protected $key = 'database-backups';

    /**
     * Objects will be started automatically while loading
     *
     * @var array
     */
    protected $autostart = [
        \DatabaseBackups\Controller\Admin::class,
        \DatabaseBackups\Controller\Cron::class,
    ];

    /**
     * Bootstrap constructor.
     * @param string $plugin
     */
    public function __construct($plugin)
    {
        $this->container = new Container($plugin, $this->key);

        foreach ($this->autostart as $class) {
            $object = new $class;

            if ($object instanceof DependencyInterface) {
                $object->setContainer($this->container);
            }

            if ($object instanceof HooksInterface) {
                $object->initHooks();
            }
        }
    }
}
