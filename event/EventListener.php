<?php

namespace NGFramer\NGFramerPHPBase\event;

use NGFramer\NGFramerPHPBase\Application;
use NGFramer\NGFramerPHPBase\defaults\exceptions\RegistryException;
use NGFramer\NGFramerPHPBase\registry\RegistryGetter;

final class EventListener
{
    /**
     * Variable storing the instance of the event listener.
     */
    private static ?EventListener $instance;


    /**
     * Function to make EventListener a singleton.
     * @return EventListener
     */
    public static function init(): EventListener
    {
        if (self::$instance === null) {
            self::$instance = new EventListener();
        }
        return self::$instance;
    }


    /**
     * Variable storing the instance of the event manager.
     * @var RegistryGetter
     */
    private RegistryGetter $registryGetter;


    /**
     * EventListener constructor.
     */
    private function __construct()
    {
        $this->registryGetter = new RegistryGetter();
    }


    /**
     * Listen to the event and then dispatch the event to be handled to the handler.
     * @param string $event
     * @throws RegistryException
     */
    final public function listen(string $event): void
    {
        // Get the handlers for the event.
        $handler = $this->registryGetter->getEventHandler($event);
        // Dispatch the handler using the handle.
        $this->dispatch($event, $handler);
    }


    /**
     * This will dispatch the event to the handler for handling.
     * @param string $event
     * @param string $handler
     * @return void
     */
    private function dispatch(string $event, string $handler): void
    {
        // Create an instance of the handler.
        $handler = new $handler();
        // Handle the event.
        $handler->handle($event);
    }
}