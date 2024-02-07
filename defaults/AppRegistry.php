<?php

// Filename: SystemEvents.php
// Location: NGFramerPHP.base/defaults/SystemEvents.php

// Caution: Do not make changes until you want to change the system level defaults.

namespace NGFramer\NGFramerPHPBase\defaults;

use NGFramer\NGFramerPHPBase\Middleware;

class AppRegistry
{
    public function __construct()
    {
        // Register the default event and event handler for logRequest.
        // $this->setEvent('logRequest', \NGFramer\NGFramerPHPBase\defaults\events\LogRequestEvent::class);
        // $this->setEventHandler('logRequest', \NGFramer\NGFramerPHPBase\defaults\eventHandlers\LogRequestHandler::class);

    }


    protected array $eventCallbacks = [];
    protected array $eventHandlerCallbacks = [];


    // Setter for Event.
    final protected function setEvent($eventName, $callback): void
    {
        $this->eventCallbacks[$eventName][] = $callback;
    }


    // Getter for Event.
    final public function getEvent(string $eventName): array
    {
        return $this->eventCallbacks[$eventName] ?? [];
    }


    // Setter for Event Handler.
    final protected function setEventHandler($eventName, $callback): void
    {
        $this->eventHandlerCallbacks[$eventName][] = $callback;
    }


    // Getter for Event Handler.
    final public function getEventHandler(string $eventName): array
    {
        return $this->eventHandlerCallbacks[$eventName] ?? [];
    }
}