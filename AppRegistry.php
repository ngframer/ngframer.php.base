<?php

namespace NGFramer\NGFramerPHPBase;

class AppRegistry
{
    protected Application $application;



    public function __construct(Application $application){
        $this->application = $application;
    }



    final public function getCallback(string $method, string $path): array
    {
        return $this->application->router->getCallback($method, $path);
    }



    final public function setCallback(string $method, string $path, array $callback): void
    {
        $this->application->router->setCallback($method, $path, $callback);
    }



    final public function setMiddleware(...$args): void
    {
        $this->application->middlewareManager->setMiddleware(...$args);
    }



    final public function getMiddleware(...$args): void
    {
        $this->application->middlewareManager->getMiddleware(...$args);
    }



    final public function setGlobalMiddleware(string ...$middleware): void
    {
        $this->application->middlewareManager->setGlobalMiddleware(...$middleware);
    }



    final public function getGlobalMiddleware(): array
    {
        return $this->application->middlewareManager->getGlobalMiddleware();
    }



    // Setter for Event.
    final public function setEvent(string $eventName, string ...$eventClasses): void
    {
        $this->application->eventManager->setEvent($eventName, ...$eventClasses);
    }



    // Getter for Event.
    final public function getEvent(string $eventName): array
    {
        return $this->application->eventManager->getEvent($eventName);
    }



    // Setter for Event Handler.
    final public function setEventHandler(string $eventClass, string $eventHandlerClass): void
    {
        $this->application->eventManager->setEventHandler($eventClass, $eventHandlerClass);
    }



    // Getter for Event Handler.
    final public function getEventHandler(string $eventClass): array
    {
        return $this->application->eventManager->getEventHandler($eventClass);
    }
}