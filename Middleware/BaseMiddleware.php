<?php

namespace NGFramer\NGFramerPHPBase\Middleware;

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
}