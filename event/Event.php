<?php

namespace NGFramer\NGFramerPHPBase\event;

abstract class Event
{
    protected string $name;

    /**
     * @throws \Exception
     */
    final public function __construct($name = null)
    {
        if (!isset($this->name)){
            if (empty($name)) Throw new \Exception("Event name is required.");
            else $this->setName($name);
        }
    }

    /**
     * @throws \Exception
     */
    final protected function setName (string $name): void
    {
        if (empty($name)) Throw new \Exception("Event name is required.");
        else $this->name = $name;
    }

    final public function getName(): string
    {
        return $this -> name;
    }
}