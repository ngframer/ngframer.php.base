<?php

namespace NGFramer\NGFramerPHPBase;

use NGFramer\NGFramerPHPExceptions\exceptions\ApiError;
use NGFramer\NGFramerPHPExceptions\handlers\ApiExceptionHandler;

// Set the display error property to E_ALL when in development.
if (APPMODE == 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
}

// Set the default error handler based on if it's an API.
if (APPTYPE == 'api') {
    //Convert the error to an exception (SqlBuilderException).
    set_error_handler([ApiError::class, 'convertToException']);
    // Set the custom exception handler for the library.
    set_exception_handler([ApiExceptionHandler::class, 'handle']);
}