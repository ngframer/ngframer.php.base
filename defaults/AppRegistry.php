<?php

// Filename: SystemEvents.php
// Location: NGFramerPHP.base/defaults/SystemEvents.php

// Caution: Do not make changes until you want to change the system level defaults.

namespace NGFramer\NGFramerPHPBase\defaults;

use NGFramer\NGFramerPHPBase\Middleware;

class AppRegistry
{
    public function __construct()
    {
        // Firstly, Set the callback and middleware for the routes.
        // $this->setCallback('get','/', [\app\controller\Index::class, 'index']);
        // self::$middleware ['get']['/'] = 'WebGuard';

        // Register the default event and event handler for logRequest.
        // $this->setEvent('logRequest', \NGFramer\NGFramerPHPBase\defaults\events\LogRequestEvent::class);
        // $this->setEventHandler('logRequest', \NGFramer\NGFramerPHPBase\defaults\eventHandlers\LogRequestHandler::class);

    }


    protected array $routeCallbacks = [];
    protected array $middlewaresMap = [];
    protected array $routeMiddlewares = [];
    protected array $globalMiddlewares = [];
    protected array $eventCallbacks = [];
    protected array $eventHandlerCallbacks = [];


    // Setter for Routes.
    final protected function setRouteCallbacks(string $method, string $path, array $callback): void
    {
        $this->routeCallbacks[$method][$path] = $callback;
    }


    final public function getRouteCallbacks($method, $path): ?array
    {
        return $this->routeCallbacks[$method][$path] ?? null;
    }


    final protected function setMiddlewaresMap(string $name, string ...$middlewareClassNames): void
    {
        foreach ($middlewareClassNames as $middlewareClassName) {
            if (!is_subclass_of($middlewareClassName, Middleware::class)) {
                throw new \InvalidArgumentException("Class $middlewareClassName is not a subclass of Middleware");
            }
        }
        $this->middlewaresMap[$name] = $middlewareClassNames;
    }

    final public function getMiddlewaresMap(string $name): ?Middleware
    {
        return $this->middlewaresMap[$name] ?? null;
    }


    // Setter for Middlewares.
    final protected function setMiddleware(string $method, string $path, string $middleware): void
    {
        $this->routeMiddlewares[$method][$path][] = $middleware;
    }


    // Getter for middleware.
    final public function getMiddleware($method, $path): ?array
    {
        return $this->middleware[$method][$path] ?? [];
    }


    // Setter for global middlewares.
    final protected function setGlobalMiddleware(string $middleware): void
    {
        if (!is_subclass_of($middleware, Middleware::class) && !array_key_exists($middleware, $this->middlewaresMap)) {
            throw new \InvalidArgumentException("Class $middleware is not a subclass of Middleware");
        }
        $this->globalMiddlewares[] = $middleware;
    }


    // Getter for global middlewares.
    final public function getGlobalMiddlewares(): array
    {
        return $this->globalMiddlewares;
    }


    // Setter for Event.
    final protected function setEvent($eventName, $callback): void
    {
        $this->eventCallbacks[$eventName][] = $callback;
    }


    // Getter for Event.
    final public function getEvent(string $eventName): array
    {
        return $this->eventCallbacks[$eventName] ?? [];
    }


    // Setter for Event Handler.
    final protected function setEventHandler($eventName, $callback): void
    {
        $this->eventHandlerCallbacks[$eventName][] = $callback;
    }


    // Getter for Event Handler.
    final public function getEventHandler(string $eventName): array
    {
        return $this->eventHandlerCallbacks[$eventName] ?? [];
    }
}