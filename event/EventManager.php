<?php

namespace NGFramer\NGFramerPHPBase\event;

class EventManager
{
    protected EventDispatcher $eventDispatcher;


    public function __construct()
    {
        $this->eventDispatcher = new EventDispatcher();
    }


    // Setter for Event Handler.
    final public function setEventHandler(string $eventClass, string $eventHandlerClass): void
    {
        $this->eventDispatcher->setHandler($eventClass, $eventHandlerClass);
    }


    final public function dispatchEvent(string $event, mixed $customData = null): void
    {
        $this->eventDispatcher->dispatch($event, $customData);
    }
}