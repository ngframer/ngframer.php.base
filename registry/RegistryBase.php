<?php

namespace NGFramer\NGFramerPHPBase\registry;

use NGFramer\NGFramerPHPBase\Application;
use NGFramer\NGFramerPHPBase\defaults\exceptions\RegistryException;
use NGFramer\NGFramerPHPBase\event\Event;
use NGFramer\NGFramerPHPBase\event\EventHandler;
use NGFramer\NGFramerPHPBase\middleware\BaseMiddleware;

class RegistryBase
{
    /**
     * Instance of the application class.
     *
     * @var Application $application
     */
    protected Application $application;


    /**
     * Current selection for the AppRegistry elements.
     * Has array elements for paths, methods, callbacks, middlewares, events, eventHandlers.
     *
     * @var array $setupContext
     */
    protected array $setupContext = [];


    /**
     * MiddlewareMap to store the middleware and their names.
     * @var array $middlewareMap
     */
    protected array $middlewareMap = [];


    /**
     * Middleware applicable for every controller.
     * @var array $globalMiddleware
     */
    protected array $globalMiddleware = [];


    /**
     * Contains the list of request type and route path with their callback.
     * @var array $routeCallback
     */
    protected array $routeCallback = [];


    /**
     * Contains the list of request type and route path with their middleware.
     * @var array $routeMiddleware
     */
    protected array $routeMiddleware = [];


    /**
     * Contains the list of controller with their middleware.
     * @var array $callbackMiddleware
     */
    protected array $callbackMiddleware = [];


    /**
     * Contains the list of event and their custom name.
     * @var array $eventMap
     */
    protected array $eventMap = [];


    /**
     * Contains the list of event handler and their custom name.
     * @var array $handlerMap
     */
    protected array $handlerMap = [];


    /**
     * Contains the list of event and their handler.
     * @var array $eventHandler
     */
    protected array $eventHandler = [];


    /**
     * Constructor for the AppRegistry class.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }


    /**
     * Function to select the path.
     *
     * @param string $path . Use path 'any' for all paths.
     * @return RegistryBase
     * @throws RegistryException
     */
    final public function selectPath(string $path): RegistryBase
    {
        // Continue only if no controller is selected.
        if (isset($this->setupContext['select']['callback'])) {
            throw new RegistryException("Callback selected, Either callback or method/path can be selected at once.");
        }
        // Check if the path has already been set.
        if (isset($this->$setupContext['select']['path'])) {
            // If the path is already set, throw a new exception.
            throw new RegistryException("Path has already been selected.");
        }
        // Now, set the path to write upon again.
        $this->setupContext['select']['path'] = $path;
        return $this;
    }


    /**
     * Function to select the method.
     *
     * @param string $method . Use method 'any' for all methods.
     * @return RegistryBase
     * @throws RegistryException
     */
    final public function selectMethod(string $method): RegistryBase
    {
        // Continue only if no controller is selected.
        if (isset($this->setupContext['select']['callback'])) {
            throw new RegistryException("Callback selected, Either callback or method/path can be selected at once.");
        }
        // Check if the method has already been set.
        if (isset($this->setupContext['select']['method'])) {
            // If the method is already set, throw a new exception.
            throw new RegistryException("Method has already been selected.");
        }
        // Now, set the method to write upon again.
        $this->setupContext['select']['method'] = $method;
        return $this;
    }


    /**
     * Function to select the callback.
     *
     * @param mixed ...$callbacks
     * @return RegistryBase
     * @throws RegistryException
     *
     * TODO: Check if the callback is callable or not, and throw an exception if not.
     */
    final public function selectCallback(mixed ...$callbacks): RegistryBase
    {
        // Loop through the callbacks selected.
        foreach ($callbacks as $callback) {
            // Continue only if no path/method is selected.
            if (isset($this->setupContext['select']['method']) or isset($this->setupContext['select']['path'])) {
                throw new RegistryException("Method/path selected, Either callback or method/path can be selected at once.");
            }
            // Check if the callback has already been set.
            if (!array_key_exists($callback, $this->setupContext['select']['callback'])) {
                // Overwrite the current selection.
                $this->setupContext['select']['callback'][] = $callback;
            }
        }
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
        if (isset($this->setupContext['set']['callback'])) {
            // If callback is already set, throw a new exception.
            throw new RegistryException("Callback has already been set for the current selection.");
        }
        $this->setupContext['set']['callback'] = $callback;
        // Now save the callback in $routeCallback array.
        $method = $this->setupContext['select']['method'] ?? 'any';
        $path = $this->setupContext['select']['path'] ?? 'any';
        $this->routeCallback[$method][$path] = $callback;
        // Clear the $setupContext.
        $this->setupContext = [];
    }


    /**
     * Function to select one or multiple middleware.
     *
     * @param string ...$middlewares
     * @return RegistryBase
     */
    final public function selectMiddleware(string ...$middlewares): RegistryBase
    {
        // Loop through the middlewares selected.
        foreach ($middlewares as $middleware) {
            // Check if the middleware has already been set.
            if (isset($this->currentSelection['middleware'])) {
                // Overwrite the current selection.
                $this->currentSelection = [];
            }
            // Now, set the middleware to write upon again.
            $this->setupContext['select']['middleware'][] = $middleware;
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
     * TODO: Check if the string is a middleware or not, and throw an exception if not.
     */
    final public function setMiddleware(string ...$middlewares): void
    {
        // Loop through the middlewares selected.
        foreach ($middlewares as $middleware) {
            // Check if the middleware has already been set.
            if (!array_key_exists($middleware, $this->setupContext['set']['middleware'])) {
                $this->setupContext['set']['middleware'][] = $middleware;
            }
            // Set on the routeMiddleware or controllerMiddleware.
            if (isset($this->setupContext['select']['path']) and isset($this->setupContext['select']['method'])) {
                $method = $this->setupContext['select']['method'];
                $path = $this->setupContext['select']['path'];
                $this->routeMiddleware[$method][$path][] = $middleware;
            } elseif (isset($this->setupContext['select']['callback'])) {
                $callback = $this->setupContext['select']['callback'];
                $this->callbackMiddleware[$callback][] = $middleware;
            } else {
                throw new RegistryException("Middleware can only be set on path/method, or callback. Use globalMiddleware to set middleware on global scope.");
            }
        }
        // Clear the $setupContext.
        $this->setupContext = [];
    }


    /**
     * Function to set the global middleware for the current selection path or controller.
     *
     * @return void
     */
    final public function setGlobalMiddleware(): void
    {
        // Add the set key in setupContext.
        $this->setupContext['set'] = 'globalMiddleware';
        // Now, Loop through the middlewares selected.
        foreach ($this->setupContext['select']['middleware'] as $middleware) {
            // Check if the middleware has already been set.
            if (!array_key_exists($middleware, $this->globalMiddleware)) {
                $this->globalMiddleware[] = $middleware;
            }
            // Set the global middleware.
            $this->globalMiddleware[] = $middleware;
        }
        // Clear the $setupContext.
        $this->setupContext = [];
    }


    /**
     * Function to name middleware or middleware group.
     *
     *  Caution:
     *  Using this function and making loops inside the registry will cause to slow down the application.
     *  While naming, select middleware base classes to make the system faster.
     *  Adding a name to already named middleware means the name will form a loop and make the application slower.
     *
     *  Though both the below mentioned ways are correct, the first one is recommended.
     *  Do: customName => Middleware1class::class, Middleware2class::class.
     *  Don't Do: customName, Middleware3class::class.
     *
     * @param string $middlewareName
     * @return void
     * @throws RegistryException
     */
    final public function nameMiddleware(string $middlewareName): void
    {
        // Add the name key in setupContext.
        $this->setupContext['name'] = $middlewareName;
        // Now, Loop through the middlewares selected.
        foreach ($this->setupContext['select']['middleware'] as $middleware) {
            // Check if the middleware is actually middleware.
            if (!is_subclass_of($middleware, BaseMiddleware::class) and !key_exists($middleware, $this->middlewareMap)) {
                throw new RegistryException("Invalid middleware, Please select an valid middleware.", 1004008);
            }
            $this->middlewareMap[$middlewareName][] = $middleware;
        }
        // Clear the $setupContext.
        $this->setupContext = [];
    }


    /**
     * Function to select the event.
     *
     * @param Event $event
     * @return RegistryBase
     * @throws RegistryException
     *
     * TODO: Check if the event is an Event or not, and throw an exception if not.
     */
    final public function selectEvent(Event $event): RegistryBase
    {
        // Check if the event has already been set.
        if (isset($this->setupContext['select']['event'])) {
            // If an event is already set, throw a new exception.
            throw new RegistryException("Event has already been selected.");
        }
        // Check if the event is actually an Event.
        if (!is_subclass_of($event, Event::class)) {
            throw new RegistryException("Invalid event, Please select an valid event.", 1004008);
        }
        // Now, set the event to write upon again.
        $this->setupContext['select']['event'] = $event;
        return $this;
    }


    /**
     * Function to name the event.
     *
     * @param string $eventName
     * @return void
     */
    final public function nameEvent(string $eventName): void
    {
        // Add the name key in setupContext.
        $this->setupContext['name'] = $eventName;
        // Now, set the event to the current selection.
        $this->eventMap[$eventName] = $this->setupContext['select']['event'];
        // Clear the $setupContext.
        $this->setupContext = [];
    }


    /**
     * Function to select the event handler.
     *
     * @param EventHandler $handler
     * @return RegistryBase
     * @throws RegistryException
     */
    final public function selectHandler(EventHandler $handler): RegistryBase
    {
        // Check if the handler is set or not.
        if (isset($this->currentSelection['handler'])) {
            // Overwrite the current selection.
            $this->currentSelection = [];
        }
        // Check if the handler is actually an EventHandler.
        if (!is_subclass_of($handler, EventHandler::class)) {
            throw new RegistryException("Invalid handler, Please select an valid handler.", 1004008);
        }
        // Now, set the handler to write upon again.
        $this->setupContext['select']['handler'] = $handler;
        return $this;
    }


    /**
     * Function to set the event handler.
     *
     * @param EventHandler $handler
     * @return void
     * @throws RegistryException
     */
    final public function setHandler(EventHandler $handler): void
    {
        // Check if the handler has already been set.
        if (isset($this->setupContext['set']['handler'])) {
            // If a handler is already set, throw a new exception.
            throw new RegistryException("Handler has already been set for the selected event.");
        }
        // Fetch the handler from the setupContext.
        $event = $this->setupContext['select']['event'];
        $handler = $this->setupContext['set']['handler'];
        // Check if the event is actually an Event.
        if (!is_subclass_of($event, Event::class)) {
            throw new RegistryException("Invalid event, Please select an valid event.", 1004008);
        }
        // Set the handler for the event.
        if (!is_subclass_of($handler, EventHandler::class)) {
            throw new RegistryException("Invalid handler, Please select an valid handler.", 1004008);
        }
        // set the handler to the current selection.
        $this->eventHandler[$event] = $handler;
        // Clear the $setupContext.
        $this->setupContext = [];
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
        // Add the name key in setupContext.
        $this->setupContext['name'] = $handlerName;
        // Fetch the handler from the setupContext.
        $handler = $this->setupContext['set']['handler'];
        // Check if the handler is actually an EventHandler.
        if (!is_subclass_of($handler, EventHandler::class)) {
            throw new RegistryException("Invalid handler, Please select an valid handler.", 1004008);
        }
        // Name a custom handler to the event handler.
        $this->handlerMap[$handlerName] = $handler;
        // Clear the $setupContext.
        $this->setupContext = [];
    }
}