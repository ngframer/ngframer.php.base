<?php

namespace NGFramer\NGFramerPHPBase;

abstract class Middleware{

    // The function to be implemented to every Middlware class.
    abstract public function execute(Request $request, callable $callback): void;
}