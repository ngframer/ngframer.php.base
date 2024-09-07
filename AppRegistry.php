<?php

namespace NGFramer\NGFramerPHPBase;

use NGFramer\NGFramerPHPBase\defaults\exceptions\MiddlewareException;
use NGFramer\NGFramerPHPBase\event\Event;
use NGFramer\NGFramerPHPBase\event\EventHandler;

class AppRegistry
{
    /**
     * Instance of the application class.
     * @var Application $application
     */
    protected Application $application;


    /**
     * Constructor for the AppRegistry class.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }


    /**
     * Function to get the controller for the given path and a given request method.
     * @param string $method
     * @param string $path
     * @return array
     */
    final public function getCallback(string $method, string $path): array
    {
        return $this->application->router->getCallback($method, $path);
    }


    /**
     * Function to set the controller for the given path with a given request method.
     * @param string $method
     * @param string $path
     * @param array $callback
     * @return void
     */
    final public function setCallback(string $method, string $path, array $callback): void
    {
        $this->application->router->setCallback($method, $path, $callback);
    }


    /**
     * Function to set the middleware for a given controller.
     * @param ...$args
     * @return void
     * @throws MiddlewareException
     */
    final public function setMiddleware(...$args): void
    {
        $this->application->middlewareManager->setMiddleware(...$args);
    }


    /**
     * Function to get the middleware for a given controller.
     * @param ...$args
     * @return void
     */
    final public function getMiddleware(...$args): void
    {
        $this->application->middlewareManager->getMiddleware(...$args);
    }


    /**
     * Function to set the global middleware applicable for all controllers.
     * @param string ...$middleware
     * @return void
     */
    final public function setGlobalMiddleware(string ...$middleware): void
    {
        $this->application->middlewareManager->setGlobalMiddleware(...$middleware);
    }


    /**
     * Function to get the global middleware applicable for all controllers.
     * @return array
     */
    final public function getGlobalMiddleware(): array
    {
        return $this->application->middlewareManager->getGlobalMiddleware();
    }


    /**
     * Function to set the event handler for a given event.
     * @param Event $event
     * @param EventHandler $handler
     * @return void
     */
    // Setter for Event Handler.
    final public function setEventHandler(Event $event, EventHandler $handler): void
    {
        $this->application->eventManager->setHandler($event, $handler);
    }


    /**
     * Function to get the event handler for a given event.
     * @param Event $event
     * @return array
     */
    final public function getEventHandler(Event $event): array
    {
        return $this->application->eventManager->getHandlers($event);
    }
}