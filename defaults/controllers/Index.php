<?php

namespace NGFramer\NGFramerPHPBase\defaults\controllers;

class Index extends \NGFramer\NGFramerPHPBase\Controller
{
    public function index(): void
    {
        $this->application->response->redirect('https://opensource.neupgroup.com/ngframerphp/initialization/controller');
    }
}