<?php

namespace NGFramer\NGFramerPHPBase\exceptions;

use NGFramer\NGFramerPHPExceptions\exceptions\supportive\_BaseException;
use Throwable;

class QueryException extends _BaseException
{
    // Constructor for the QueryException.
    public function __construct($message, $code = 0, ?Throwable $previous = null, int $statusCode = 500, array $details = [])
    {
        // Call the parent constructor for exception.
        parent::__construct($message, $code, $previous, $statusCode, $details);
    }
}