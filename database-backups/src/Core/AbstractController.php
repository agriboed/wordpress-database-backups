<?php

namespace DatabaseBackups\Core;

use DatabaseBackups\Interfaces\DependencyInterface;
use DatabaseBackups\Interfaces\HooksInterface;

/**
 * Class AbstractController
 * @package DatabaseBackups\Controller
 */
class AbstractController implements DependencyInterface, HooksInterface
{
    /**
     * @var $container Container
     */
    protected $container;

    /**
     *
     */
    public function initHooks()
    {
    }

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}