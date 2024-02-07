<?php

// Filename: EventDispatcher.php
// Location: ngframerphp/base/event/EventDispatcher.php

namespace NGFramer\NGFramerPHPBase\event;

class EventDispatcher
{
    // Not to be touched. Set using the addHandler().
    // Contains the list of callback function classes.
    // Simply $handlers are callback classes for events.
    protected array $handlers = [];


    final protected function addHandler(Event $event, EventHandler $handler): void
    {
        // Get the name of the event.
        $eventName = $event -> getName();
        // Add the handler to the list of handlers.
        $this -> handlers[$eventName][] = $handler;
    }


    final public function dispatch(Event $event): void
    {
        $eventName = $event -> getName();
        if (isset($this->handlers[$eventName])) {
            foreach ($this -> handlers[$eventName] as $handler) {
                $handler = new $handler();
                $handler -> execute();
            }
        }
    }
}