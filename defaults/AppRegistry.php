<?php

// Filename: SystemEvents.php
// Location: NGFramerPHP.base/defaults/AppRegistry.php
// Caution: Do not make changes until you want to change the system level defaults.

use NGFramer\NGFramerPHPBase\Application;

$appRegistry = Application::$application->appRegistry;


// Firstly, Set the callback and middleware for the routes.
$appRegistry->setCallback('get','/', [\NGFramer\NGFramerPHPBase\defaults\controllers\Index::class, 'index']);
$appRegistry->setCallback('get','/error', [\NGFramer\NGFramerPHPBase\defaults\controllers\Error::class, 'index']);
// $appRegistry->setMiddleware('get','/') = 'WebGuard';

// Register the default event and event handler 'logRequest'.
$appRegistry->setEvent('logRequest', \NGFramer\NGFramerPHPBase\defaults\events\LogRequestEvent::class);
$appRegistry->setEventHandler(\NGFramer\NGFramerPHPBase\defaults\events\LogRequestEvent::class, \NGFramer\NGFramerPHPBase\defaults\eventHandlers\LogRequestHandler::class);