<?php

namespace DatabaseBackups\Exceptions;

/**
 * Class Exception
 * @package DatabaseBackups\Exceptions
 */
class Exception extends \RuntimeException
{
    public function __construct($message = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}