<?php

namespace NGFramer\NGFramerPHPBase;

class AppRegistry
{
    protected array $event = [];
    protected array $eventHandler = [];
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