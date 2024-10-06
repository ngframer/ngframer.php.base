<?php

namespace NGFramer\NGFramerPHPBase\middleware;

use NGFramer\NGFramerPHPBase\Request;

abstract class BaseMiddleware
{
    /**
     * The list of callbacks that should be excluded from the middleware.
     * @var array $exceptOn
     */
    protected array $exceptOn = [
        [\NGFramer\NGFramerPHPBase\defaults\controllers\Error::class]
    ];


    /**
     * Process the middleware and execute the callback if the middleware allows it.
     * @param Request $request
     * @param callable $callback
     * @return void
     */
    final public function process(Request $request, callable $callback): void
    {
        if (!$this->shouldBeExcluded($callback)) {
            $this->execute($request, $callback);
        }
    }


    /**
     * The function to be implemented to every Middleware class.
     * @param Request $request
     * @param callable $callback
     * @return void
     */
    abstract public function execute(Request $request, callable $callback): void;


    // Execute the callback if the current callback can be run after the middleware.
    final public function run(Request $request, callable $callback): void
    {
        call_user_func($callback);
    }


    /**
     * Check if the current callback should be excluded from the middleware.
     * @param callable $callback
     * @return bool
     */
    protected function shouldBeExcluded(callable $callback): bool
    {
        foreach ($this->exceptOn as $except) {
            // Case 1: Check for the exact callback match (controller + action)
            if ($this->matchesCallback($callback, $except)) {
                return true;
            } // Case 2: Check if exempting based on another nested Middleware
            elseif (is_array($except) && is_subclass_of($callback, BaseMiddleware::class)) {
                $middlewareClass = get_class($callback);
                if ((new $middlewareClass)->shouldBeExcluded($callback)) {
                    return true;
                }
            } // Case 3: Check if excluding an entire controller class
            elseif (is_string($except) && is_subclass_of($callback[0], $except)) {
                return true;
            }
        }
        return false;
    }


    /**
     * Check if the current callback matches the exclusion rule.
     * @param callable $currentCallback
     * @param callable $exclusionRule
     * @return bool
     */
    protected function matchesCallback(callable $currentCallback, callable $exclusionRule): bool
    {
        if (!is_array($currentCallback) || !is_array($exclusionRule)) return false;
        else return $currentCallback[0] === $exclusionRule[0] && $currentCallback[1] === $exclusionRule[1];
    }
}