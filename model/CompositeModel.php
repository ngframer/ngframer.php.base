<?php

namespace NGFramer\NGFramerPHPBase\model;

abstract class CompositeModel
{
    // Property to save the instance of the class.
    protected static ?self $instance = null;


    /**
     * Function to initialize the instance of the class.
     * @return static. Returns instance of this class.
     */
    final public static function init(): static
    {
        $calledClass = static::class;
        if (!isset(self::$instances[$calledClass])) {
            self::$instances[$calledClass] = new static();
        }
        return self::$instances[$calledClass];
    }


    /**
     * Constructor function to just not allow the class to be created instance of.
     */
    protected function __construct()
    {
    }
}