<?php

namespace NGFramer\NGFramerPHPBase\Registry;

use NGFramer\NGFramerPHPBase\Defaults\Exceptions\RegistryException;
use NGFramer\NGFramerPHPBase\Event\Event;
use NGFramer\NGFramerPHPBase\Event\EventHandler;
use NGFramer\NGFramerPHPBase\Middleware\BaseMiddleware;

class RegistrySetter extends Registry
{

    /**
     * RegistrySetter constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Function to select the path.
     *
     * @param string $path . Use path 'any' for all paths.
     * @return RegistrySetter
     * @throws RegistryException
     */
    final public function selectPath(string $path): RegistrySetter
    {
        // Check if the path has already been set.
        if (isset(self::$setupContext['select']['path'])) {
            // If the path is already set, throw a new exception.
            throw new RegistryException("Path has already been selected.", 1004008, 'base.registry.pathAlreadySelected');
        }

        // Continue only if no controller is selected.
        if (isset(self::$setupContext['select']['callback'])) {
            throw new RegistryException("Callback selected, Either callback or method/path can be selected at once.", 1004009, 'base.registry.callbackOrMethodPathSelected');
        }

        // Now, set the path to write upon again.
        self::$setupContext['select']['path'] = $path;
        return $this;
    }


    /**
     * Function to select the method.
     *
     * @param string $method . Use method 'any' for all methods.
     * @return RegistrySetter
     * @throws RegistryException
     */
    final public function selectMethod(string $method): RegistrySetter
    {
        // Check if the method has already been set.
        if (isset(self::$setupContext['select']['method'])) {
            // If the method is already set, throw a new exception.
            throw new RegistryException("Method has already been selected.", 1004010, 'base.registry.methodAlreadySelected');
        }

        // Continue only if no controller is selected.
        if (isset(self::$setupContext['select']['callback'])) {
            throw new RegistryException("Callback selected, Either callback or method/path can be selected at once.", 1004011, 'base.registry.callbackOrMethodPathSelected.2');
        }

        // Now, set the method to write upon again.
        self::$setupContext['select']['method'] = $method;
        return $this;
    }


    /**
     * Function to set the callback.
     *
     * @param mixed $callback
     * @return void
     * @throws RegistryException
     *
     * TODO: Check if the callback is an valid callback or not.
     */
    final public function setCallback(mixed $callback): void
    {

        // Check if the callback has already been set.
        if (isset(self::$setupContext['set']['callback'])) {
            // If callback is already set, throw a new exception.
            throw new RegistryException("Callback has already been set for the current selection.", 1004012, 'base.registry.callbackAlreadySet');
        }

        // Save the callback to the setupContext.
        self::$setupContext['set']['callback'] = $callback;

        // Fetch the method and path from the setupContext.
        $method = self::$setupContext['select']['method'] ?? 'any';
        $path = self::$setupContext['select']['path'] ?? 'any';

        // Now save the callback in $routeCallback array.
        self::$routeCallback[$method][$path] = $callback;

        // Clear the $setupContext.
        self::$setupContext = [];
    }


    /**
     * Function to select one or multiple middleware.
     *
     * @param string ...$middlewares
     * @return RegistrySetter
     * @throws RegistryException
     */
    final public function selectMiddleware(string ...$middlewares): RegistrySetter
    {
        // Loop through the middlewares selected.
        foreach ($middlewares as $middleware) {
            // Check if the middleware is actually middleware.
            if (!is_subclass_of($middleware, BaseMiddleware::class) or !key_exists($middleware, self::$middlewareMap)) {
                throw new RegistryException("Invalid middleware, Please select an valid middleware.", 1004013, 'base.registry.invalidMiddleware');
            }

            // Now, set the middleware to write upon again.
            self::$setupContext['select']['middleware'][] = $middleware;
        }
        return $this;
    }


    /**
     * Function to set the middleware for request or controller.
     *
     * @param string ...$middlewares
     * @return void
     * @throws RegistryException
     *
     */
    final public function setMiddleware(string ...$middlewares): void
    {
        // Loop through the middlewares selected.
        foreach ($middlewares as $middleware) {
            // Check if the middleware passed is actually middleware.
            if (!is_subclass_of($middleware, BaseMiddleware::class) or !key_exists($middleware, self::$middlewareMap)) {
                throw new RegistryException("Invalid middleware, Please select an valid middleware.", 1004014, 'base.registry.invalidMiddleware.2');
            }

            // Check if the middleware has already been set.
            if (!array_key_exists($middleware, self::$setupContext['set']['middleware'])) {
                self::$setupContext['set']['middleware'][] = $middleware;
            }

            // Set on the routeMiddleware or controllerMiddleware.
            if (isset(self::$setupContext['select']['path']) or isset(self::$setupContext['select']['method'])) {
                $method = self::$setupContext['select']['method'] ?? 'any';
                $path = self::$setupContext['select']['path'] ?? 'any';
                self::$routeMiddleware[$method][$path][] = $middleware;
            } elseif (isset(self::$setupContext['select']['callback'])) {
                $callback = self::$setupContext['select']['callback'];
                self::$callbackMiddleware[$callback][] = $middleware;
            } else {
                throw new RegistryException("Middleware can only be set on path/method, or callback.", 1004015, 'base.registry.middlewareSetError');
            }
        }

        // Clear the $setupContext.
        self::$setupContext = [];
    }


    /**
     * Function to set the global middleware for the current selection path or controller.
     *
     * @return void
     */
    final public function setGlobalMiddleware(): void
    {
        // Add the set key in setupContext.
        self::$setupContext['set'] = 'globalMiddleware';

        // Now, Loop through the middlewares selected.
        foreach (self::$setupContext['select']['middleware'] as $middleware) {
            // Check if the middleware has already been set.
            if (!array_key_exists($middleware, self::$globalMiddleware)) {
                self::$globalMiddleware[] = $middleware;
            }
            // Set the global middleware.
            self::$globalMiddleware[] = $middleware;
        }

        // Clear the $setupContext.
        self::$setupContext = [];
    }


    /**
     * Function to name middleware or middleware group.
     *
     *  Caution:
     *  Using this function and making loops inside the registry will cause to slow down the application.
     *  While naming, select middleware base classes to make the system faster.
     *  Adding a name to already named middleware means the name will form a loop and make the application slower.
     *
     *  Though both the below-mentioned ways are correct, the first one is recommended.
     *  Do: customName ⇒ FirstMiddleWareClass::class, SecondMiddlewareClass::class.
     *  Don't Do: customName, ThirdMiddlewareClass::class.
     *
     * @param string $middlewareName
     * @return void
     * @throws RegistryException
     */
    final public function nameMiddleware(string $middlewareName): void
    {
        // Check if the middleware has already been set.
        if (!isset(self::$setupContext['select']['middleware'])) {
            throw new RegistryException("Middleware has not been selected.", 1004016, 'base.registry.middlewareNotSelected');
        }

        // Add the name key in setupContext.
        self::$setupContext['name'] = $middlewareName;

        // Now, Loop through the middlewares selected.
        foreach (self::$setupContext['select']['middleware'] as $middleware) {
            // Check if the middleware is actually middleware.
            if (!is_subclass_of($middleware, BaseMiddleware::class) and !key_exists($middleware, self::$middlewareMap)) {
                throw new RegistryException("Invalid middleware. Select a valid middleware.", 1004017, 'base.registry.invalidMiddleware.3');
            }
            self::$middlewareMap[$middlewareName][] = $middleware;
        }

        // Clear the $setupContext.
        self::$setupContext = [];
    }


    /**
     * Function to select the event.
     *
     * @param string $event
     * @return RegistrySetter
     * @throws RegistryException
     *
     * TODO: Check if the event is an Event or not, and throw an exception if not.
     */
    final public function selectEvent(string $event): RegistrySetter
    {
        // Check if the event has already been selected.
        if (isset(self::$setupContext['select']['event'])) {
            // If an event is already selected, throw a new exception.
            throw new RegistryException("Event has already been selected.", 1004018, 'base.registry.eventAlreadySelected');
        }

        // Check if the event is actually an Event.
        if (!is_subclass_of($event, Event::class)) {
            throw new RegistryException("Invalid event. Please select a valid event.", 1004019, 'base.registry.invalidEvent.2');
        }

        // Now, set the event to write upon again.
        self::$setupContext['select']['event'] = $event;
        return $this;
    }


    /**
     * Function to name the event.
     *
     * @param string $eventName
     * @return void
     * @throws RegistryException
     */
    final public function nameEvent(string $eventName): void
    {
        // Check if the event has already been selected.
        if (!isset(self::$setupContext['select']['event'])) {
            throw new RegistryException("Event has not been selected.", 1004020, 'base.registry.eventNotSelected');
        }

        // Add the name key in setupContext.
        self::$setupContext['name'] = $eventName;

        // Now, set the event to the current selection.
        self::$eventMap[$eventName] = self::$setupContext['select']['event'];

        // Clear the $setupContext.
        self::$setupContext = [];
    }


    /**
     * Function to select the event handler.
     *
     * @param string $handler
     * @return RegistrySetter
     * @throws RegistryException
     */
    final public function selectHandler(string $handler): RegistrySetter
    {
        // Check if the handler is selected or not.
        if (isset(self::$setupContext['select']['handler'])) {
            throw new RegistryException("Handler has already been selected.", 1004021, 'base.registry.eventHandlerAlreadySelected');
        }

        // Check if the handler is actually an EventHandler.
        if (!is_subclass_of($handler, EventHandler::class)) {
            throw new RegistryException("Invalid handler, Please select a valid handler.", 1004022, 'base.registry.invalidEventHandler.2');
        }

        // Now, set the handler to write upon again.
        self::$setupContext['select']['handler'] = $handler;
        return $this;
    }


    /**
     * Function to set the event handler.
     *
     * @param string $handler
     * @return void
     * @throws RegistryException
     */
    final public function setHandler(string $handler): void
    {
        // Check if an event has been selected or not.
        if (!isset(self::$setupContext['select']['event'])) {
            // If an event is not selected, throw a new exception.
            throw new RegistryException("Event has not been selected.", 1004023, 'base.registry.eventNotSelected.2');
        }

        // Check if the handler has already been set.
        if (isset(self::$setupContext['set']['handler'])) {
            // If a handler is already set, throw a new exception.
            throw new RegistryException("Handler has already been set for the selected event.", 1004024, 'base.registry.eventHandlerAlreadySelected.2');
        }

        // Fetch the handler from the setupContext.
        $event = self::$setupContext['select']['event'];
        $handler = self::$setupContext['set']['handler'];

        // Check if the settled handler is actually an EventHandler.
        if (!is_subclass_of($handler, EventHandler::class)) {
            throw new RegistryException("Invalid handler, Please select a valid handler.", 1004025, 'base.registry.invalidEventHandler.3');
        }
        // set the handler to the selected event.
        self::$eventHandler[$event] = $handler;

        // Clear the $setupContext.
        self::$setupContext = [];
    }


    /**
     * Function to name the event handler.
     *
     * @param string $handlerName
     * @return void
     * @throws RegistryException
     */
    final public function nameHandler(string $handlerName): void
    {
        // Check if a handler has been set or not.
        if (!isset(self::$setupContext['select']['handler'])) {
            throw new RegistryException("Handler has not been selected.", 1004026, 'base.registry.eventHandlerNotSelected');
        }

        // Fetch the handler from the setupContext.
        $handler = self::$setupContext['select']['handler'];

        // Check if the handler is actually an EventHandler.
        if (!is_subclass_of($handler, EventHandler::class)) {
            throw new RegistryException("Invalid handler, Please select a valid handler.", 1004027, 'base.registry.invalidEventHandler.4');
        }

        // Add the name key in setupContext.
        self::$setupContext['name'] = $handlerName;

        // Name a custom handler to the event handler.
        self::$handlerMap[$handlerName] = $handler;

        // Clear the $setupContext.
        self::$setupContext = [];
    }
}
