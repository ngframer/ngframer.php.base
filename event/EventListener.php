<?php

namespace NGFramer\NGFramerPHPBase\event;

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
     * @var EventManager
     */
    private EventManager $manager;


    /**
     * EventListener constructor.
     */
    private function __construct()
    {
        $this->manager = new EventManager();
    }


    /**
     * Listen to the event and then dispatch the event to be handled to the handler.
     * @param Event $event
     */
    final public function listen(Event $event): void
    {
        // Get the handlers for the event.
        $handlers = $this->manager->getHandlers($event);

        // Loop through the handlers and dispatch the event to the handler.
        foreach ($handlers as $handler) {
            $this->dispatch($event, $handler);
        }
    }


    /**
     * This will dispatch the event to the handler for handling.
     * @param Event $event
     * @param EventHandler $handler
     * @return void
     */
    private function dispatch(Event $event, EventHandler $handler): void
    {
        $handler->handle($event);
    }
}