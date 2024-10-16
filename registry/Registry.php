<?php

namespace NGFramer\NGFramerPHPBase\registry;

use NGFramer\NGFramerPHPBase\Application;

class Registry
{
    /**
     * Current selection for the AppRegistry elements.
     * Has array elements for paths, methods, callbacks, middlewares, events, eventHandlers.
     *
     * @var array $setupContext
     */
    protected static array $setupContext = [];


    /**
     * MiddlewareMap to store the middleware and their names.
     * @var array $middlewareMap
     */
    protected static array $middlewareMap = [];


    /**
     * Middleware applicable for every controller.
     * @var array $globalMiddleware
     */
    protected static array $globalMiddleware = [];


    /**
     * Contains the list of request type and route path with their callback.
     * @var array $routeCallback
     */
    protected static array $routeCallback = [];


    /**
     * Contains the list of request type and route path with their middleware.
     * @var array $routeMiddleware
     */
    protected static array $routeMiddleware = [];


    /**
     * Contains the list of controller with their middleware.
     * @var array $callbackMiddleware
     */
    protected static array $callbackMiddleware = [];


    /**
     * Contains the list of event and their custom name.
     * @var array $eventMap
     */
    protected static array $eventMap = [];


    /**
     * Contains the list of event handler and their custom name.
     * @var array $handlerMap
     */
    protected static array $handlerMap = [];


    /**
     * Contains the list of event and their handler.
     * @var array $eventHandler
     */
    protected static array $eventHandler = [];


    /**
     * Private Constructor for the AppRegistry class.
     */
    protected function __construct()
    {
    }
}