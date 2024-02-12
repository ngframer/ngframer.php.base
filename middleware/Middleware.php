<?php

namespace NGFramer\NGFramerPHPBase\middleware;

use NGFramer\NGFramerPHPBase\Request;

abstract class Middleware{
    protected array $exceptOn = [
        [\NGFramer\NGFramerPHPBase\defaults\controllers\Error::class]
    ];



    final public function process(Request $request, callable $callback): void
    {
        if (!$this->shouldBeExcluded($callback)) {
            $this->process($request, $callback);
        }
    }



    // The function to be implemented to every Middlware class.
    abstract public function execute(Request $request, callable $callback): void;



    // Execute the callback if the current callback can be run after the middleware.
    final public function run(Request $request, callable $callback): void
    {
        call_user_func($callback);
    }




    protected function shouldBeExcluded(callable $callback): bool
    {
        foreach ($this->exceptOn as $except) {
            // Case 1: Check for exact callback match (controller + action)
            if ($this->matchesCallback($callback, $except)) {
                return true;
            }
            // Case 2: Check if exempting based on another nested Middleware
            elseif (is_array($except) && is_subclass_of($callback, Middleware::class)) {
                $middlewareClass = get_class($callback);
                if ((new $middlewareClass)->shouldBeExcluded($callback)) {
                    return true;
                }
            }
            // Case 3: Check if excluding an entire controller class
            elseif (is_string($except) && is_subclass_of($callback[0], $except)) {
                return true;
            }
        }
        return false;
    }



    protected function matchesCallback(callable $currentCallback, callable $exclusionRule): bool
    {
        if (!is_array($currentCallback) || !is_array($exclusionRule)) return false;
        else return  $currentCallback[0] === $exclusionRule[0] && $currentCallback[1] === $exclusionRule[1];
    }
}