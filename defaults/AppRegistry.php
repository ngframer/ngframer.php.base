<?php

// Filename: SystemEvents.php
// Location: NGFramerPHP.base/defaults/AppRegistry.php
// Caution: Do not make changes until you want to change the system level defaults.

use NGFramer\NGFramerPHPBase\Application;

// Get an instance of registry class.
$appRegistry = Application::$application->registry;

// Firstly, Set the callback and middleware for the routes.
$appRegistry->selectPath('/')->selectMethod('get')->setCallback([\NGFramer\NGFramerPHPBase\defaults\controllers\Index::class, 'index']);
$appRegistry->selectPath('/error')->setCallback([\NGFramer\NGFramerPHPBase\defaults\controllers\Error::class, 'index']);