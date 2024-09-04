<?php

namespace NGFramer\NGFramerPHPBase;

use app\config\ApplicationConfig;
use NGFramer\NGFramerPHPBase\defaults\exceptions\ConfigurationException;
use NGFramer\NGFramerPHPExceptions\exceptions\ApiError;
use NGFramer\NGFramerPHPExceptions\handlers\ApiExceptionHandler;
use NGFramer\NGFramerPHPBase\defaults\exceptions\DependencyException;

if (!class_exists('app\config\ApplicationConfig')) {
    throw new DependencyException("The 'ngframer.php' dependency is missing. This project cannot function independently.", 1001001);
}

// Set the display error property to E_ALL when in development.
// Get the appMode.
$appMode = ApplicationConfig::get('appMode');

// Check for appMode.
if ($appMode == 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} elseif ($appMode == 'production') {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
} else {
    throw new ConfigurationException("The 'appMode' property must be set to either 'development' or 'production' in the 'ApplicationConfig' class.", 1002002);
}

// Set the default error handler based on if it's an API.
$appType = ApplicationConfig::get('appType');
if ($appType == 'api') {
    //Convert the error to an exception (SqlBuilderException).
    set_error_handler([new ApiError(), 'convertToException']);
    // Set the custom exception handler for the library.
    set_exception_handler([new ApiExceptionHandler(), 'handle']);
}