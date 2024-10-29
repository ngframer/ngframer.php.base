<?php

namespace NGFramer\NGFramerPHPBase\Event;

abstract class EventHandler
{
    /**
     * Base EventHandler constructor.
     */
    public function __construct()
    {
    }


    /**
     * Base EventHandler handle function.
     * @param string $event
     */
    public function handle(string $event)
    {
    }
}