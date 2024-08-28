<?php

namespace NGFramer\NGFramerPHPBase\event;

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
     */
    public function handle(Event $event)
    {
    }
}