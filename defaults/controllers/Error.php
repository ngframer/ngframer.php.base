<?php

namespace NGFramer\NGFramerPHPBase\defaults\controllers;

class Error extends \NGFramer\NGFramerPHPBase\controller\Controller
{
    public function index(): void
    {
        $this->redirect('https://opensource.neupgroup.com/ngframerphp/initialization/controller');
    }
}