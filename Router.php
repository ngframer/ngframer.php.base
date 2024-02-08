<?php

namespace NGFramer\NGFramerPHPBase;

use Exception;

class Router
{
    public Application $application;
    public Request $request;

    public mixed $callbackMethod;

    public mixed $callbackController;


    public function __construct(Application $application, Request $request)
    {
        $this->application = $application;
        $this->request = $request;
    }


    // Function determining URL path, and method, and execute the callback.

    /**
     * @throws Exception
     */
    public function handleRoute(): void
    {
        $method = $this->request->getMethod();
        $path = $this->request->getPath();

        // Determine the callback associated with the requested path and method.
        $callback = $this->application->appRegistry->getRouteCallbacks($method, $path);
        try {
            $individualMiddlewares = $this->application->appRegistry->getMiddleware($method, $path) ?? [];
            $globalMiddlewares = $this->application->appRegistry->getGlobalMiddlewares();
            $middlewares = array_merge($individualMiddlewares, $globalMiddlewares);
        } catch (Exception $e) {
            Throw new Exception($e->getMessage() . " in the route '$path'.");
        }

        // Middleware and callback exists.
        if (!empty($middlewares) && $callback !== null) {
            // Loop across all the middlewares.
            foreach ($middlewares as $middleware) {
                if (!$middleware instanceof Middleware) {
                    throw new Exception("The Middleware '$middleware' must be an instance of Middleware class.");
                } else {
                    // Execute the middleware.
                    $middleware->execute($this->request, function () use ($callback) {
                        $this->executeCallback($callback);
                    });
                }
            }
        }
        // No middleware but callback exists.
        else if ($callback !== null && $middlewares == null) {
            // Check if a valid callback exists, is string, and is callable.
            // Only possible for functions not in any class.
            $this->executeCallback($callback);
        }
        // No middleware and no callback exist.
        else {
            $this->executeErrorCallback();
        }
    }


    // Used only for error callback by handleRoute() method.
    private function executeErrorCallback(): void
    {
        $callback = $this->application->appRegistry->getRouteCallbacks('get', '/error');
        $callback[0] = new $callback[0]($this->application);
        call_user_func($callback);
    }


    // Callback execution using executeCallback() method.
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
}