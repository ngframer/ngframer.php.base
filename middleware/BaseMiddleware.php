<?php

namespace NGFramer\NGFramerPHPBase\middleware;

use NGFramer\NGFramerPHPBase\Application;
use NGFramer\NGFramerPHPBase\defaults\exceptions\CallbackException;
use NGFramer\NGFramerPHPBase\Request;

abstract class BaseMiddleware
{
    /**
     * The function to be implemented by every Middleware class.
     * @param Request $request
     * @param callable $callback
     * @return void
     */
    abstract public function execute(Request $request, callable $callback): void;


    /**
     * The function does be executing the executing and then the callback.
     * @param Request $request
     * @param callable $callback
     * @return void
     * @throws CallbackException
     */
    final public function process(Request $request, callable $callback): void
    {
        $this->execute($request, $callback);
        Application::$application->router->executeCallback($callback);
    }
}