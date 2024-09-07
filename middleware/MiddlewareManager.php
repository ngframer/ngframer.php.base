<?php

namespace NGFramer\NGFramerPHPBase\middleware;

use InvalidArgumentException;
use NGFramer\NGFramerPHPBase\defaults\exceptions\MiddlewareException;

class MiddlewareManager
{
    protected array $middlewareMap = [];
    protected array $routeMiddleware = [];
    protected array $globalMiddleware = [];


    /**
     * Setter for Middleware.
     * @param ...$args
     * @return void
     * @throws MiddlewareException
     *
     * Sets the middleware for the route if route(method and path) is provided.
     * Sets the middleware map for the middleware name if middleware name and middleware class is provided.
     * Accepts values in the following:
     * 1. setMiddleware('get', '/', 'WebGuard');
     * 2. setMiddleware('get', '/', WebGuard::class);
     * 3. setMiddleware('get', '/', ['WebGuard', 'WebGuard2']);
     * 4. setMiddleware('WebGuard', WebGuard::class);
     * 5. setMiddleware('WebGuard', ['WebGuard', 'WebGuard2']);
     */
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


    /**
     * Process the middleware.
     * @param string $method
     * @param string $path
     * @param string $middlewareClass
     * @return void
     * @throws MiddlewareException
     */
    private function processMiddleware(string $method, string $path, string $middlewareClass): void
    {
        if (is_subclass_of($middlewareClass, Middleware::class)) {
            $this->routeMiddleware[$method][$path][] = $middlewareClass;
        } elseif (array_key_exists($middlewareClass, $this->middlewareMap)) {
            $middlewareClass = $this->middlewareMap[$middlewareClass];
            $this->routeMiddleware[$method][$path][] = $middlewareClass;
        } else {
            throw new MiddlewareException("Invalid middleware", 1004003);
        }
    }


    /**
     * Set the middleware map.
     * @param string $middlewareName
     * @param string ...$middlewareClasses
     * @return void
     * @throws MiddlewareException
     */
    private function setMiddlewareMap(string $middlewareName, string ...$middlewareClasses): void
    {
        foreach ($middlewareClasses as $middlewareClass) {
            if (!is_subclass_of($middlewareClass, Middleware::class)) {
                throw new MiddlewareException("Class $middlewareClass is not a subclass of Middleware", 1004004);
            }
        }
        $this->middlewareMap[$middlewareName] = $middlewareClasses;
    }


    /**
     * Get the middleware.
     * @param ...$args
     * @return array|null
     */
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


    /**
     * Get the middleware from the middleware class name.
     * @param string $middlewareName
     * @return array|null
     */
    private function getMiddlewareFromClass(string $middlewareName): ?array
    {
        $middlewareClass = $this->middlewareMap[$middlewareName] ?? null;
        if ($middlewareClass !== null) {
            return (array)$middlewareClass;
        }
        return null;
    }


    /**
     * Get the middleware for the route.
     * @param string $method
     * @param string $path
     * @return array|null
     */
    private function getMiddlewareForRoute(string $method, string $path): ?array
    {
        $routeMiddleware = $this->routeMiddleware[$method][$path] ?? [];
        if (!empty($routeMiddleware)) {
            return $routeMiddleware;
        }
        return null;
    }


    /**
     * Setter for global middlewares.
     * @param string ...$middlewareClasses
     * @return void
     * @throws MiddlewareException
     *
     * Sets the global middleware for the application.
     * Accepts values in the following:
     * 1. setGlobalMiddleware('WebGuard');
     * 2. setGlobalMiddleware(WebGuard::class);
     * 3. setGlobalMiddleware(['WebGuard', 'WebGuard2']);
     * 4. setGlobalMiddleware(WebGuard::class, WebGuard2::class);
     */
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
                    throw new MiddlewareException("Invalid middleware: $middlewareClass", 1004005);
                }
            } else {
                throw new MiddlewareException("Invalid middleware: $middlewareClass", 1004006);
            }
        }
    }


    /**
     * Getter for global middlewares.
     * @return array
     */
    // Getter for global middlewares.
    final public function getGlobalMiddleware(): array
    {
        return $this->globalMiddleware;
    }
}