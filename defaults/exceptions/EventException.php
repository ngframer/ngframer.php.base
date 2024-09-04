<?php

namespace NGFramer\NGFramerPHPBase\defaults\exceptions;

use Throwable;

class EventException extends AppException
{
    /**
     * Constructor for the EventException.
     * @param $message
     * @param $code
     * @param Throwable|null $previous
     * @param int $statusCode
     * @param array $details
     */
    public function __construct($message, $code = 0, ?Throwable $previous = null, int $statusCode = 500, array $details = [])
    {
        // Call the parent constructor for exception.
        parent::__construct($message, $code, $previous, $statusCode, $details);
    }
}