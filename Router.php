<?php

namespace NGFramer\NGFramerPHPBase;

use app\config\ApplicationConfig;
use Exception;
use NGFramer\NGFramerPHPBase\controller\Controller;
use NGFramer\NGFramerPHPBase\defaults\exceptions\CallbackException;
use NGFramer\NGFramerPHPBase\defaults\exceptions\MiddlewareException;
use NGFramer\NGFramerPHPBase\defaults\exceptions\RegistryException;
use NGFramer\NGFramerPHPBase\registry\RegistryGetter;

class Router
{
    /**
     * Instance of the application class from constructor.
     * @var Application $application
     */
    public Application $application;


    /**
     * Instance of the request class from constructor.
     * @var Request $request
     */
    public Request $request;


    /**
     * Instance of the registry getter.
     * @var RegistryGetter $registry
     */
    public RegistryGetter $registry;


    /**
     * Constructor for the Router class.
     * @param Application $application
     * @param Request $request
     */
    public function __construct()
    {
        $this->application = Application::$application;
        $this->request = $this->application->request;
        $this->registry = new RegistryGetter();
    }


    /**
     * Function determining URL path, and method, and execute the callback.
     *
     * @throws MiddlewareException
     * @throws CallbackException
     * @throws RegistryException
     *
     * TODO: Look out for the working of getting the middleware for the controller and the path and method request.
     * TODO: Check if the callback is a controller or a closure/callback.
     * TODO: Check if the executeCallback and process function works.
     */
    public function handleRoute(): void
    {
        $method = $this->request->getMethod();
        $path = $this->request->getPath();

        // Determine the callback associated with the requested path and method.
        $callback = $this->registry->getCallback($method, $path);

        // Check if the callback exists.
        if (empty($callback)) {
            throw new CallbackException("No callback associated for path '$path'.", 1004004);
        }

        try {
            // Get all the individual and global middlewares for the requested path.
            $individualMiddlewares = $this->registry->getMiddleware($method, $path, $callback);
            $globalMiddlewares = $this->registry->getGlobalMiddleware();
            $middlewares = array_merge($individualMiddlewares, $globalMiddlewares);
        } catch (Exception $e) {
            throw new MiddlewareException("Error processing middleware for route '$path'. Error: {$e->getMessage()}", 1004001);
        }

        // If the middleware exists.
        if (!empty($middlewares)) {
            // Loop across all the middlewares.
            foreach ($middlewares as $middleware) {
                // Check if the middleware is an instance or just an class string.
                if (!$middleware instanceof BaseMiddleware) {
                    $middleware = new $middleware();
                }
                // Execute the middleware.
                $middleware->execute($this->request, $callback);
            }
        }

        // Check for the callback type.
        if (is_string($callback)) {
            if (is_callable($callback)) {
                call_user_func($callback);
            } else {
                throw new CallbackException("Invalid callback passed.", 1004004);
            }
        } elseif (is_array($callback)) {
            // Callback => [0] = Controller, [1] = Method.
            $callbackClass = $callback[0];
            $callback[0] = new $callback[0];
            // Check if callback is an subclass of the Controller class.
            if (!is_subclass_of($callback[0], Controller::class)) {
                throw new CallbackException("Invalid callback passed. The callback is not an instance of Controller.", 1004004);
            }
            // Let's call the controller method.
            is_callable($callback) ? call_user_func($callback) : throw new CallbackException("Invalid callback passed. $callbackClass->$callback[1]()", 1004004);
        } else {
            throw new CallbackException("Invalid callback passed.", 1004004);
        }
    }
}