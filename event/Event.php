<?php

namespace NGFramer\NGFramerPHPBase\event;

abstract class Event
{
    protected string $name;

    /**
     * @throws \Exception
     */
    final public function __construct($name)
    {
        if (!isset($this->name)){
            if (empty($name)) Throw new \Exception("Event name is required.");
            else $this->setName($name);
        } elseif (empty($this->name)) Throw new \Exception("Event name is required.");
        else Throw new \Exception("Something went wrong with the event name.");
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