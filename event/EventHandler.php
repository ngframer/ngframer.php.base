<?php

namespace NGFramer\NGFramerPHPBase\event;

abstract class EventHandler
{
    abstract public function execute($eventData, $customData): void;
}