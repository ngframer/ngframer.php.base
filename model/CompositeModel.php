<?php

namespace ngfrmaer\NGFramerPHPBase\model;

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
        if (self::$instance == null) {
            self::$instance = new static();
        }
        return static::$instance;
    }


    /**
     * Constructor function to just not allow the class to be created instance of.
     */
    protected function __construct()
    {
    }
}