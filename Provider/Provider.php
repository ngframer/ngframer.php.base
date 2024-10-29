<?php

namespace NGFramer\NGFramerPHPBase\Provider;

class Provider
{
    /**
     * Using the multiton pattern.
     * Variable to store instances of the provider classes.
     *
     * @var array $instances.
     */
    private static array $instances = [];


    /**
     * Protected constructor.
     *
     * Only extending class can override it.
     */
    protected function __construct()
    {
    }


    /**
     *Function to initialize the provider.
     *
     * @return static
     */
    public static function init(): static
    {
        // Get the current class.
        $class = static::class;

        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class();
        }
        return self::$instances[$class];
    }
}