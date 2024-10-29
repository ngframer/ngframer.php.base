<?php

namespace NGFramer\NGFramerPHPBase\Defaults\Exceptions;

use NGFramer\NGFramerPHPExceptions\Exceptions\BaseException;
use Throwable;

class JobException extends BaseException
{
    /**
     * Constructor for the JobException.
     * @param string $message
     * @param int $code
     * @param string $label
     * @param Throwable|null $previous
     * @param int $statusCode
     * @param array $details
     */
    public function __construct(string $message, int $code = 0, string $label = '', ?Throwable $previous = null, int $statusCode = 500, array $details = [])
    {
        // Call the parent constructor for exception.
        parent::__construct($message, $code, $label, $previous, $statusCode, $details);
    }
}