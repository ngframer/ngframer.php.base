<?php

namespace NGFramer\NGFramerPHPBase;

abstract class Middleware{
    
    // Array contains the list of middlewares defined.
	private static array $middlewaresMap = [
        'Auth' => \ngframer\ngframerphp\middlewares\AuthMiddleware::class,
        'WebGuard' => \ngframer\ngframerphp\middlewares\WebGuardMiddleware::class,
    ];







    // Array contains the list of middleware that are executed on all requests.
    private static array $globalMiddlewares = ['WebGuard'];





    // The function to be implemented to every Middlware class.
    abstract public function execute(Request $request, callable $callback): void;






    // Getter for middlewaresMap.
    public static function getMiddlewareMap($middlewareName): ?string
    {

        return self::$middlewaresMap[$middlewareName] ?? null;
    }




    // Getter for middleware object instance.
    // Returns the middleware object.
    // If the middleware is not defined in the middlewaresMap, it will throw an exception.
    public static function getMiddleware($middlewareName): Middleware
    {
        if (class_exists($middlewareName)){
            $middleware = new $middlewareName();
        } else{
            $middlewareClass = self::getMiddlewareMap($middlewareName);
            if (!class_exists($middlewareClass) || $middlewareClass == null){
                Throw new \Exception("The middleware $middlewareName is not defined.");
            } else{
                $middleware = new $middlewareClass();
            }
        }
        return $middleware;
    }





    // Getter for middlewaresMap.
    // Returns array of global middleware objects.
    // Uses the getMiddleware() function to get the middleware object.
    // Can throw an exception if the middleware is not defined in the middlewaresMap.
    public static function getGlobalMiddlewares(): ?array
    {
        if (self::$globalMiddlewares !== null) {
            foreach (self::$globalMiddlewares as $globalMiddlewareKey => $globalMiddleware) {
                self::$globalMiddlewares[$globalMiddlewareKey] = self::getMiddleware($globalMiddleware);
            }
        }
        return self::$globalMiddlewares;
    }


}