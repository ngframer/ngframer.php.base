<?php

namespace NGFramer\NGFramerPHPBase\defaults\controllers;

class Error extends \NGFramer\NGFramerPHPBase\Controller
{
    public function index(): void
    {
        $this->application->response->redirect('https://opensource.neupgroup.com/ngframerphp/initialization/controller');
    }
}