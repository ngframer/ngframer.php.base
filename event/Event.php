<?php

namespace NGFramer\NGFramerPHPBase\event;

abstract class Event
{
    protected string $name;
    protected mixed $data;

    /**
     * @throws \Exception
     */
    final public function __construct($data = null)
    {
        if (!isset($this->name)){
            if (empty($name)) Throw new \Exception("Event name is required.");
            else $this->setName($name);
        }
        $this->data = $data;
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

    final public function getData(): mixed
    {
        return $this->data;
    }
}