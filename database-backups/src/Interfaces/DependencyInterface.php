<?php

namespace DatabaseBackups\Interfaces;

use DatabaseBackups\Core\Container;

interface DependencyInterface
{
    public function setContainer(Container $container);
}