<?php

// Filename: SystemEvents.php
// Location: NGFramerPHP.base/defaults/SystemEvents.php

// Caution: Do not make changes until you want to change the system level defaults.

namespace NGFramer\NGFramerPHPBase\defaults;

class AppRegistry extends \NGFramer\NGFramerPHPBase\AppRegistry
{
    public function __construct()
    {
        // Firstly, Set the callback and middleware for the routes.
        // $this->setCallback('get','/', [\app\controller\Index::class, 'index']);
        // self::$middleware ['get']['/'] = 'WebGuard';

        // Register the default event and event handler 'logRequest'.
        $this->setEvent('logRequest', \NGFramer\NGFramerPHPBase\defaults\events\LogRequestEvent::class);
        $this->setEventHandler('logRequest', \NGFramer\NGFramerPHPBase\defaults\eventHandlers\LogRequestHandler::class);

    }
}