<?php

namespace NGFramer\NGFramerPHPBase;

class AppRegistry
{
    protected array $routeCallback = [];
    protected array $middlewareMap = [];
    protected array $routeMiddleware = [];
    protected array $globalMiddleware = [];
    protected array $event = [];
    protected array $eventHandler = [];


    // TODO: Check if the callback is a valid callback.
    // Setter for Route Callback.
    public final function setCallback(string $method, string $path, array $callback): void
    {
        $this->routeCallback[$method][$path] = $callback;
    }


    // Getter for Route Callback.
    final public function getCallback(string $method, string $path): array
    {
        return $this->routeCallback[$method][$path] ?? [];
    }


    // Setter for Middleware.
    // Sets the middleware for the route if route(method and path) is provided.
    // Sets the middleware map for the middleware name if middleware name and middleware class is provided.
    // Accepts values in the following:
    // => 1. setMiddleware('get', '/', 'WebGuard');
    // => 2. setMiddleware('get', '/', WebGuard::class);
    // => 3. setMiddleware('get', '/', ['WebGuard', 'WebGuard2']);
    // => 4. setMiddleware('WebGuard', WebGuard::class);
    // => 5. setMiddleware('WebGuard', ['WebGuard', 'WebGuard2']);
    final public function setMiddleware(...$args): void
    {
        if (count($args) === 3) {
            $method = $args[0];
            $path = $args[1];
            $middlewareClass = $args[2];
            if (is_array($middlewareClass)) {
                foreach ($middlewareClass as $singleMiddleware) {
                    $this->processMiddleware($method, $path, $singleMiddleware);
                }
            } else {
                $this->processMiddleware($method, $path, $middlewareClass);
            }
        } elseif (count($args) === 2) {
            $middlewareName = $args[0];
            $middlewareClasses = $args[1];
            if (is_array($middlewareClasses)) {
                foreach ($middlewareClasses as $middlewareClass) {
                    $this->setMiddlewareMap($middlewareName, $middlewareClass);
                }
            } else {
                $middlewareClass = $middlewareClasses;
                $this->setMiddlewareMap($middlewareName, $middlewareClass);
            }
        }
    }


    private function processMiddleware(string $method, string $path, string $middlewareClass): void
    {
        if (is_subclass_of($middlewareClass, Middleware::class)) {
            $this->setMiddleware($method, $path, $middlewareClass::class);
        } elseif (array_key_exists($middlewareClass, $this->middlewareMap)) {
            $middlewareClass = $this->middlewareMap[$middlewareClass];
            $this->setMiddleware($method, $path, $middlewareClass);
        } else {
            throw new \InvalidArgumentException("Invalid middleware");
        }
    }


    private function setMiddlewareMap(string $middlewareName, string ...$middlewareClasses): void
    {
        foreach ($middlewareClasses as $middlewareClass) {
            if (!is_subclass_of($middlewareClass, Middleware::class)) {
                throw new \InvalidArgumentException("Class $middlewareClass is not a subclass of Middleware");
            }
        }
        $this->middlewareMap[$middlewareName] = $middlewareClasses;
    }

    final public function getMiddleware(...$args): ?array
    {
        if (count($args) === 1) {
            $argument = $args[0];
            if (is_string($argument)) {
                // If the argument is a middleware name, use the Middleware Map.
                return $this->getMiddlewareFromClass($argument);
            }
        } elseif (count($args) === 2) {
            $method = $args[0];
            $path = $args[1];
            // If the arguments are method and path, use the Route Middleware.
            return $this->getMiddlewareForRoute($method, $path);
        }
        return null;
    }

    private function getMiddlewareFromClass(string $middlewareName): ?array
    {
        $middlewareClass = $this->middlewareMap[$middlewareName] ?? null;
        if ($middlewareClass !== null) {
            return (array)$middlewareClass;
        }
        return null;
    }

    private function getMiddlewareForRoute(string $method, string $path): ?array
    {
        $routeMiddleware = $this->routeMiddleware[$method][$path] ?? [];
        if (!empty($routeMiddleware)) {
            return $routeMiddleware;
        }
        return null;
    }



    // Setter for global middlewares.
    // Sets the global middleware for the application.
    // Accepts values in the following:
    // => 1. setGlobalMiddleware('WebGuard');
    // => 2. setGlobalMiddleware(WebGuard::class);
    // => 3. setGlobalMiddleware(['WebGuard', 'WebGuard2']);
    // => 4. setGlobalMiddleware(WebGuard::class, WebGuard2::class);
    final public function setGlobalMiddleware(string ...$middlewareClasses): void
    {
        foreach ($middlewareClasses as $middlewareClass) {
            if (is_string($middlewareClass) && is_subclass_of($middlewareClass, Middleware::class)) {
                // If the input is a middleware class, directly add it to globalMiddleware.
                $this->globalMiddleware[] = $middlewareClass;
            } elseif (is_string($middlewareClass)) {
                // If the input is a middleware name, use the Middleware Map.
                $middlewareClass = $this->middlewareMap[$middlewareClass] ?? null;
                if ($middlewareClass !== null) {
                    $this->globalMiddleware[] = $middlewareClass;
                } else {
                    throw new \InvalidArgumentException("Invalid middleware: $middlewareClass");
                }
            } else {
                throw new \InvalidArgumentException("Invalid middleware: $middlewareClass");
            }
        }
    }


    // Getter for global middlewares.
    final public function getGlobalMiddleware(): array
    {
        return $this->globalMiddleware;
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
    public final function getEventHandler(string $eventClass): array
    {
        if (!is_subclass_of($eventClass, \NGFramer\NGFramerPHPBase\event\EventHandler::class)) {
            throw new \InvalidArgumentException("Invalid Event Handler");
        }
        return $this->eventHandler[get_class(new $eventClass)] ?? [];
    }
}