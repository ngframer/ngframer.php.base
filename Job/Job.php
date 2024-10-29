<?php

namespace NGFramer\NGFramerPHPBase\job;

abstract class Job
{
    /**
     * Variable to store the instance of the child classes.
     * @var Job|null $instance .
     */
    private static ?Job $instance = null;


    /**
     * Initialize the class for performing jobs.
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
     * If you need more functions, Try to keep other functions private to maintain abstraction.
     * @return void
     */
    public function execute()
    {
        // The implementation of the job will be done here.
    }
}