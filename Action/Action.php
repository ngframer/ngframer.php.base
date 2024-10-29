<?php

namespace NGFramer\NGFramerPHPBase\Action;

abstract class Action
{
    /**
     * Variable to store the instance of the child classes.
     * @var Action|null $instance .
     */
    private static ?Action $instance = null;


    /**
     * Initialize the class for performing actions.
     * @return $this
     */
    final public static function init(): self
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }


    /**
     * Private constructor to prevent instantiation.
     * Final to prevent overriding.
     */
    final private function __construct()
    {
    }


    /**
     * Perform the action through this method.
     * The function can be overridden in the child class.
     * Try to keep the other functions private to maintain abstraction.
     */
    public function execute()
    {
    }
}