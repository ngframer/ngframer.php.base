<?php

// Filename: Registry.php
// Location: NGFramerPHP.base/defaults/Registry.php
// Caution: Do not make changes until you want to change the system level defaults.

use NGFramer\NGFramerPHPBase\registry\RegistrySetter;
use NGFramer\NGFramerPHPBase\defaults\controllers\Index;
use NGFramer\NGFramerPHPBase\defaults\controllers\Error;

// Get an instance of registry class.
$registry = new RegistrySetter();

// Firstly, Set the callback and middleware for the routes.
$registry->selectPath('/')->selectMethod('get')->setCallback([Index::class, 'index']);
$registry->selectPath('/error')->selectMethod('get')->setCallback([Error::class, 'index']);