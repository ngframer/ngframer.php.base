<?php

// Filename: LogRequestHandler.php
// Location: NGFramerPHP.base/defaults/events/handlers/LogRequestHandler.php

namespace NGFramer\NGFramerPHPBase\defaults\eventHandlers;

class LogRequestHandler extends \NGFramer\NGFramerPHPBase\event\EventHandler
{
    public function execute($data = null): void
    {
        // Log the request
        $log = new \NGFramer\NGFramerPHPBase\log\Log();
        $log -> log("Request: " . $_SERVER['REQUEST_URI']);
    }
}