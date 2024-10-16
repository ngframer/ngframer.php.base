<?php

// Filename: Registry.php
// Location: NGFramerPHP.base/defaults/Registry.php
// Caution: Do not make changes until you want to change the system level defaults.

use NGFramer\NGFramerPHPBase\Application;

// Get an instance of registry class.
$registry = new \NGFramer\NGFramerPHPBase\registry\RegistrySetter();

// Firstly, Set the callback and middleware for the routes.
$registry->selectPath('/')->selectMethod('get')->setCallback([\NGFramer\NGFramerPHPBase\defaults\controllers\Index::class, 'index']);
$registry->selectPath('/error')->setCallback([\NGFramer\NGFramerPHPBase\defaults\controllers\Error::class, 'index']);