<?php

namespace DatabaseBackups\Controller;


use DatabaseBackups\Core\Container;
use DatabaseBackups\Interfaces\DependencyInterface;

class ControllerAbstract implements DependencyInterface
{
    /**
     * @var $container Container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}
