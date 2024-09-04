<?php

namespace NGFramer\NGFramerPHPBase\defaults\controllers;

class Error extends \NGFramer\NGFramerPHPBase\controller\Controller
{
    /**
     * Default sample controller, redirects to the documentation page.
     * @return void
     */
    public function index(): void
    {
        $this->redirect('https://opensource.neupgroup.com/ngframerphp/initialization/controller');
    }
}