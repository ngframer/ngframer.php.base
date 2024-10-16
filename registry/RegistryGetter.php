<?php

namespace NGFramer\NGFramerPHPBase\registry;

use NGFramer\NGFramerPHPBase\defaults\exceptions\RegistryException;
use NGFramer\NGFramerPHPBase\event\Event;
use NGFramer\NGFramerPHPBase\event\EventHandler;
use NGFramer\NGFramerPHPBase\middleware\BaseMiddleware;

class RegistryGetter extends Registry
{
    /**
     * RegistryGetter constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Search for a callback in the registry.
     * Sequence of how the search happens, priority order is as:
     * 1. Specified Method and Specified Path.
     * 2. Any Method and Specified Path.
     * 3. Specified Method and Any Path.
     * 4. Any Method and Any Path.
     *
     * @param string $method
     * @param string $path
     * @return mixed
     * @throws RegistryException
     */
    final public function getCallback(string $method, string $path): mixed
    {
        // Priority 1: Specified path and Specified method.
        if (isset(self::$routeCallback[$method][$path])) {
            return self::$routeCallback[$method][$path];
        }

        // Priority 2: Specified path and any method.
        if (isset(self::$routeCallback['any'][$path])) {
            return self::$routeCallback['any'][$path];
        }

        // Priority 3: Any path and Specified method.
        if (isset(self::$routeCallback[$method]['any'])) {
            return self::$routeCallback[$method]['any'];
        }

        // Priority 4: Any path and Any method.
        if (isset(self::$routeCallback['any']['any'])) {
            return self::$routeCallback['any']['any'];
        }

        // If no callback is found, throw an exception.
        throw new RegistryException("No callback found for the method $method and the path $path");
    }


    /**
     * Get the middleware for the route and callback combined.
     *
     * @param string $method
     * @param string $path
     * @param mixed|null $callback
     * @return array
     * @throws RegistryException
     */
    final public function getMiddleware(string $method, string $path, mixed $callback = null): array
    {
        // Getting middleware for the route.
        $routeMiddleware = $this->getMiddlewareForRoute($method, $path);

        // Getting middleware for the callback.
        $callbackMiddleware = $this->getMiddlewareForCallback($callback);

        // Now merge and get the middleware class for custom middleware name if any.
        return $this->getMiddlewareClasses(array_merge($routeMiddleware, $callbackMiddleware));
    }


    /**
     * Get the middleware for the callback.
     *
     * @param mixed $callback
     * @return array
     */
    private function getMiddlewareForCallback(mixed $callback): array
    {
        // Check if the callback is null.
        if (is_null($callback)) {
            return [];
        }

        // Check if any middleware exists.
        if (empty(self::$callbackMiddleware) or !isset(self::$callbackMiddleware[$callback])) {
            return [];
        }

        // Return the middleware for the callback if it exists.
        return self::$callbackMiddleware[$callback];
    }

    /**
     * Get the middleware for the route.
     *
     * @param string $method
     * @param string $path
     * @return array
     */
    private function getMiddlewareForRoute(string $method, string $path): array
    {
        // Variables to store the middlewares.
        $routeMiddleware = [];

        // Getting middleware for asked method and path.
        // For any path using any methods.
        if (isset(self::$routeMiddleware['any']['any'])) {
            $routeMiddleware = self::$routeMiddleware['any']['any'];
        }

        // For any path using a specified method.
        if (isset(self::$routeMiddleware[$method]['any'])) {
            $routeMiddleware = array_merge($routeMiddleware, self::$routeMiddleware[$method]['any']);
        }

        // For a specified path using any methods.
        if (isset(self::$routeMiddleware['any'][$path])) {
            $routeMiddleware = array_merge($routeMiddleware, self::$routeMiddleware['any'][$path]);
        }

        // For a specified path using a specified method.
        if (isset(self::$routeMiddleware[$method][$path])) {
            $routeMiddleware = array_merge($routeMiddleware, self::$routeMiddleware[$method][$path]);
        }

        // Return the middleware for the route.
        return $routeMiddleware;
    }


    /**
     * Function to get the middlewares classes list.
     *
     * @return array
     * @throws RegistryException
     */
    final public function getGlobalMiddleware(): array
    {
        return $this->getMiddlewareClasses(self::$globalMiddleware);
    }


    /**
     * Get the middleware class for the middleware names.
     *
     * Caution:
     * This method is not meant to be used directly. It's a helper method for the getMiddleware method.
     *
     * @param array $middlewares
     * @return array
     * @throws RegistryException
     */
    private function getMiddlewareClasses(array $middlewares): array
    {
        // Initialize an empty array to store the middleware class names.
        $middlewareClasses = [];

        // Loop through each middleware provided in the $middlewares array.
        foreach ($middlewares as $middleware) {
            // Check if the middleware is a subclass of BaseMiddleware.
            // This confirms that the provided $middleware is a valid class name.
            if (is_subclass_of($middleware, BaseMiddleware::class)) {
                // If it's a valid class, add it directly to the result array.
                $middlewareClasses[] = $middleware;
            } else {
                // If it's not a class, assume it's a middleware name that needs to be resolved.
                // Check if the middleware name exists in the $middlewareMap (which holds the mapping).
                if (!isset(self::$middlewareMap[$middleware])) {
                    // If the middleware name is not found in the registry, throw an exception.
                    throw new RegistryException("Middleware '$middleware' does not exist in the middlewareMap.");
                } else {
                    // If the middleware name is found in the map, resolve it to the corresponding class name.
                    $middlewareClasses[] = self::$middlewareMap[$middleware];
                }
            }
        }

        // Return the array of resolved middleware class names.
        return $middlewareClasses;
    }


    /**
     * Function to get the event class for the custom event name.
     *
     * @param string $eventName
     * @return string
     * @throws RegistryException
     */
    private function getEventClass(string $eventName): string
    {
        if (isset(self::$eventMap[$eventName])) {
            $eventClass = self::$eventMap[$eventName];
            // Check if the event class is a subclass of BaseEvent.
            if (is_subclass_of($eventClass, Event::class)) {
                return $eventClass;
            } else {
                throw new RegistryException("Event $eventName is not a subclass of BaseEvent.");
            }
        } else {
            throw new RegistryException("No event found for the name $eventName");
        }
    }

    /**
     * Function to get the event handler class for the custom event handler name.
     *
     * @param string $handlerName
     * @return string
     * @throws RegistryException
     */
    private function getHandlerClass(string $handlerName): string
    {
        if (isset(self::$handlerMap[$handlerName])) {
            $eventHandlerClass = self::$handlerMap[$handlerName];
            // Check if the event handler class is a subclass of BaseEventHandler.
            if (is_subclass_of($eventHandlerClass, EventHandler::class)) {
                return $eventHandlerClass;
            } else {
                throw new RegistryException("Event Handler $handlerName is not a subclass of BaseEventHandler.");
            }
        } else {
            throw new RegistryException("No event handler found for the name $handlerName");
        }
    }


    /**
     * Function to get the event handler for any event.
     *
     * @param string $event
     * @return string
     * @throws RegistryException
     */
    final public function getEventHandler(string $event): string
    {
        // Check if the event is a subclass of BaseEvent.
        if (is_subclass_of($event, Event::class)) {
            $eventClass = $event;
        } else {
            $eventClass = $this->getEventClass($event);
        }

        // Check if the event handler is a subclass of BaseEventHandler.
        $eventHandler = self::$eventHandler[$eventClass] ?? self::$eventHandler[$event] ?? throw new RegistryException("No event handler found for the event $event");
        if (is_subclass_of($eventHandler, EventHandler::class)) {
            $eventHandlerClass = $eventClass;
        } else {
            $eventHandlerClass = $this->getHandlerClass($eventHandler);
        }

        // Return the event handler class.
        return $eventHandlerClass;
    }
}