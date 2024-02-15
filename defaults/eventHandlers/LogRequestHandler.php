<?php

// Filename: LogRequestHandler.php
// Location: NGFramerPHP.base/defaults/events/handlers/LogRequestHandler.php

namespace NGFramer\NGFramerPHPBase\defaults\eventHandlers;

class LogRequestHandler extends \NGFramer\NGFramerPHPBase\event\EventHandler
{
    public function execute($eventData, $customData): void
    {
        // Converting the data to json format for logging.
        $eventData = json_encode($eventData);
        $customData = json_encode($customData);
        // Getting the request URI for appropriate logging.
        $uri = $_SERVER['REQUEST_URI'];
        // Logging the request made, the event data and the custom data.
        error_log("Request made on: $uri");
        error_log("EventData passed is: $eventData");
        error_log("CustomData passed is: $customData.");
        // Logging the event has been working. Add break line for better readability.
        error_log(PHP_EOL);
    }
}