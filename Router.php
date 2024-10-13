<?php

namespace NGFramer\NGFramerPHPBase;

use Exception;
use NGFramer\NGFramerPHPBase\defaults\exceptions\CallbackException;
use NGFramer\NGFramerPHPBase\defaults\exceptions\MiddlewareException;
use NGFramer\NGFramerPHPBase\defaults\exceptions\RegistryException;

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
     * Constructor for the Router class.
     * @param Application $application
     * @param Request $request
     */
    public function __construct(Application $application, Request $request)
    {
        $this->application = $application;
        $this->request = $request;
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
        $callback = $this->application->registryGetter->getCallback($method, $path);
        try {
            // Get all the individual and global middlewares for the requested path.
            $individualMiddlewares = $this->application->registryGetter->getMiddleware($method, $path, $callback) ?? [];
            $globalMiddlewares = $this->application->registryGetter->getGlobalMiddleware();
            $middlewares = array_merge($individualMiddlewares, $globalMiddlewares);
        } catch (Exception $e) {
            throw new MiddlewareException("Error processing middleware for route '$path'. Error: {$e->getMessage()}", 1004001);
        }

        // If the middleware exists.
        if (!empty($middlewares)) {
            // Loop across all the middlewares.
            foreach ($middlewares as $middleware) {
                // Check if the middleware is an instance or just an class string.
                if (!$middleware instanceOf BaseMiddleware) {
                    $middleware = new $middleware();
                }
                // Execute the middleware.
                $middleware->execute($this->request, $callback);
            }
        }

        // Check if the callback exists.
        if (empty($callback)) {
            throw new CallbackException("No callback associated for path '$path'.", 1004004);
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
            $callback[0] = new $callback[0]($this->application);

            if (is_callable($callback)) {
                call_user_func($callback);
            } else {
                throw new CallbackException("Invalid callback passed.", 1004004);
            }
        } else {
            throw new CallbackException("Invalid callback passed.", 1004004);
        }
    }
}