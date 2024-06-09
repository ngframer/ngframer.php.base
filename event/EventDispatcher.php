<?php

// Filename: EventDispatcher.php
// Location: ngframerphp/base/event/EventDispatcher.php

namespace NGFramer\NGFramerPHPBase\event;

class EventDispatcher
{
    // Not to be touched. Set using the setHandler().
    // Contains the list of callback function classes.
    // Simply $handlers are callback classes for events.
    protected array $handlers = [];
    protected array $data = [];


    final public function setHandler(string $eventClass, string $eventHandlerClass): void
    {
        if (!is_subclass_of($eventClass, \NGFramer\NGFramerPHPBase\event\Event::class)) {
            throw new \InvalidArgumentException("Invalid Event");
        }
        if (!is_subclass_of($eventHandlerClass, \NGFramer\NGFramerPHPBase\event\EventHandler::class)) {
            throw new \InvalidArgumentException("Invalid Event Handler");
        }
        $eventInstance = new $eventClass;
        $eventName = $eventInstance->getName();
        $eventData = $eventInstance->getData();
        // Assign event handler and event data to the event name.
        $this->handlers [$eventName] = $eventHandlerClass;
        $this->data [$eventName] = $eventInstance->getData();
    }


    /**
     * @param string $event => send either the event name or the event class.
     * @return mixed
     */
    private function getHandler(string $event): mixed
    {
        $eventName = $event;
        if (is_subclass_of($event, \NGFramer\NGFramerPHPBase\event\Event::class)) {
            $eventInstance = new $event;
            $eventName = $eventInstance->getName();
        }
        // Assign event handler to the event name.
        return $this->handlers[$eventName] ?? null;
    }


    final public function dispatch(string $event, mixed $customData = null): void
    {
        $eventHandler = $this->getHandler($event);
        $eventHandler = new $eventHandler();
        $eventData = $this->data[$event] ?? null;
        $eventHandler->execute($eventData, $customData);
    }
}