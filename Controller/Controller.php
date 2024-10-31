<?php

namespace NGFramer\NGFramerPHPBase\Controller;

use App\Config\ApplicationConfig;
use NGFramer\NGFramerPHPBase\Application;
use NGFramer\NGFramerPHPBase\Request;
use NGFramer\NGFramerPHPBase\Response\Response;

class Controller
{
    /**
     * Instance of the application.
     *
     * @var Application
     */
    public Application $application;


    /**
     * Instance of the response.
     *
     * @var Response $response
     */
    public Response $response;


    /**
     * Instance of the request.
     */
    public Request $request;


    /**
     * Constructor for the controller base class.
     */
    public function __construct()
    {
        $this->application = Application::$application;
        $this->response = Application::$application->response;
        $this->request = Application::$application->request;
    }
}