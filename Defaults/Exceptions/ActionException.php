<?php

namespace NGFramer\NGFramerPHPBase\defaults\exceptions;

use NGFramer\NGFramerPHPExceptions\exceptions\BaseException;
use Throwable;

class ActionException extends BaseException
{
    /**
     * Constructor for the ActionException.
     * @param string $message
     * @param int $code
     * @param string $label;
     * @param Throwable|null $previous
     * @param int $statusCode
     * @param array $details
     */
    public function __construct(string $message, int $code = 0, string $label = '',  ?Throwable $previous = null, int $statusCode = 500, array $details = [])
    {
        // Call the parent constructor for exception.
        parent::__construct($message, $code, $label, $previous, $statusCode, $details);
    }
}