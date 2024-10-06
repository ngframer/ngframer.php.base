<?php

namespace NGFramer\NGFramerPHPBase\defaults\exceptions;

use NGFramer\NGFramerPHPExceptions\exceptions\supportive\_BaseException;
use Throwable;

class CallbackException extends AppException
{
    /**
     * Constructor for the BaseException.
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