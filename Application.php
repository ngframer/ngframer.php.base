<?php

namespace NGFramer\NGFramerPHPBase;

class Application
{
	// Initialization of the variables used across the application.
	public static Application $application;
	public Request $request;
	public Router $router;
	public Controller $controller;
    public Session $session;
	public Response $response;


	// Instantiation of the __construct function.
	public function __construct()
	{
		self::$application = $this;
		$this->request = new Request();
		$this->router = new Router($this, $this->request);
		$this->controller = new Controller($this);
        $this->session = new Session();
        $this->response = new Response();
	}


	// Run the application by first looking are the request.
	public function run(): void
    {
        // Route the request to the controller and get the response
        $this->router->handleRoute();
    }
}