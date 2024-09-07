<?php

namespace NGFramer\NGFramerPHPBase;

use Exception;
use NGFramer\NGFramerPHPBase\defaults\exceptions\MiddlewareException;
use NGFramer\NGFramerPHPBase\middleware\Middleware;

class Router
{
    protected array $routeCallback = [];
    public Application $application;
    public Request $request;
    public mixed $callbackMethod;
    public mixed $callbackController;


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
     * Setter for route callbacks.
     * Functions takes in the method, path, and callback and sets a callback to the method and path.
     * @param string $method
     * @param string $path
     * @param array $callback
     * @return void
     * TODO: Check if the callback is a valid callback.
     */
    public final function setCallback(string $method, string $path, array $callback): void
    {
        $this->routeCallback[$method][$path] = $callback;
    }


    /**
     * Getter for route callbacks.
     * Function takes in the method and path and returns the callback associated with that method and path.
     * @param string $method
     * @param string $path
     * @return array
     */
    final public function getCallback(string $method, string $path): array
    {
        return $this->routeCallback[$method][$path] ?? [];
    }


    /**
     * Function determining URL path, and method, and execute the callback.
     * @throws MiddlewareException
     */
    public function handleRoute(): void
    {
        $method = $this->request->getMethod();
        $path = $this->request->getPath();

        // Determine the callback associated with the requested path and method.
        $callback = $this->getCallback($method, $path);
        try {
            $individualMiddlewares = $this->application->appRegistry->getMiddleware($method, $path) ?? [];
            $globalMiddlewares = $this->application->appRegistry->getGlobalMiddleware();
            $middlewares = array_merge($individualMiddlewares, $globalMiddlewares);
        } catch (Exception $e) {
            throw new MiddlewareException("Error processing middleware for route '{$path}'. Error: {$e->getMessage()}", 1004001);
        }

        // If the middleware and callback exist.
        if (!empty($middlewares) && !empty($callback)) {
            // Loop across all the middlewares.
            foreach ($middlewares as $middleware) {
                if (!$middleware instanceof Middleware) {
                    throw new MiddlewareException("Invalid middleware: '{$middleware}'. It must be an instance of the 'Middleware' class.", 1004002);
                } else {
                    // Execute the middleware.
                    $middleware->process($this->request, function () use ($callback) {
                        $this->executeCallback($callback);
                    });
                }
            }
        }
        // No middleware but callback exists.
        else if (!empty($callback) && empty($middlewares)) {
            // Check if a valid callback exists, is string, and is callable.
            // Only possible for functions not in any class.
            $this->executeCallback($callback);
        }
        // No middleware and no callback exist.
        else {
            $this->executeErrorCallback();
        }
    }


    /**
     * Function to execute the callback.
     * @param $callback
     * @return void
     */
    private function executeCallback($callback): void
    {
        if ($callback && is_string($callback)) {
            if (is_callable($callback)) {
                call_user_func($callback);
            } else {
                $this->executeErrorCallback();
            }
        }

        // Check if a valid callback exists, is array, and if it's callable.
        else if ($callback && is_array($callback)) {
            $callback[0] = new $callback[0]($this->application);
            $this->callbackController = $callback[0];
            $this->callbackMethod = $callback[1];
            if (is_callable($callback)) {
                call_user_func($callback);
            } else {
                $this->executeErrorCallback();
            }
        } else {
            $this->executeErrorCallback();
        }
    }


    /**
     * Function to execute the error callback.
     * @return void
     */
    private function executeErrorCallback(): void
    {
        $callback = $this->getCallback('get', '/error');
        $callback[0] = new $callback[0]($this->application);
        call_user_func($callback);
    }
}