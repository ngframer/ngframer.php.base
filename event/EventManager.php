<?php

namespace NGFramer\NGFramerPHPBase\event;

final class EventManager
{
    /**
     * Contains the list of handlers for each event.
     * @var array|null $handlers
     */
    private ?array $handlers = [];


    /**
     * Function adds a handler to the list of handlers for a given event.
     * @param Event $event
     * @param EventHandler $handler
     * @return void
     */
    public function setHandler(Event $event, EventHandler $handler): void
    {
        $this->handlers[get_class($event)][] = $handler;
    }


    /**
     * Function returns the list of handlers for a given event.
     * @param Event $event
     * @return array|null
     */
    public function getHandlers(Event $event): ?array
    {
        return $this->handlers[get_class($event)] ?? null;
    }
}