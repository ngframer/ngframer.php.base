<?php

namespace NGFramer\NGFramerPHPBase;

class EventManager
{
    protected array $event = [];
    protected array $eventHandler = [];



    // Setter for Event.
    final public function setEvent(string $eventName, string ...$eventClasses): void
    {
        foreach ($eventClasses as $eventClass) {
            if (!is_subclass_of($eventClass, \NGFramer\NGFramerPHPBase\event\Event::class)) {
                throw new \InvalidArgumentException("Invalid Event $eventClass");
            }
            $this->event[$eventName][] = $eventClass;
        }
    }



    // Getter for Event.
    final public function getEvent(string $eventName): array
    {
        return $this->event[$eventName] ?? [];
    }



    // Setter for Event Handler.
    final public function setEventHandler(string $eventClass, string $eventHandlerClass): void
    {
        if (!is_subclass_of($eventClass, \NGFramer\NGFramerPHPBase\event\Event::class)) {
            throw new \InvalidArgumentException("Invalid Event");
        }
        if (!is_subclass_of($eventHandlerClass, \NGFramer\NGFramerPHPBase\event\EventHandler::class)) {
            throw new \InvalidArgumentException("Invalid Event Handler");
        }
        $this->eventHandler[get_class(new $eventClass)] = $eventHandlerClass;
    }



    // Getter for Event Handler.
    final public function getEventHandler(string $eventClass): array
    {
        if (!is_subclass_of($eventClass, \NGFramer\NGFramerPHPBase\event\EventHandler::class)) {
            throw new \InvalidArgumentException("Invalid Event Handler");
        }
        return $this->eventHandler[get_class(new $eventClass)] ?? [];
    }
}