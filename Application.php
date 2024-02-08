<?php

namespace NGFramer\NGFramerPHPBase;

use NGFramer\NGFramerPHPBase\defaults\AppRegistry;

class Application
{
	// Initialization of the variables used across the application.
	public static Application $application;
	public Request $request;
	public Router $router;
	public Controller $controller;
    public Session $session;
	public Response $response;
    public AppRegistry $appRegistry;


    // Instantiation of the __construct function.
	public function __construct()
	{
		self::$application = $this;
		$this->request = new Request();
		$this->router = new Router($this, $this->request);
		$this->controller = new Controller($this);
        $this->session = new Session();
        $this->response = new Response();
        // Get all the routes, middlewares, and events.
        $this->getAppRegistry();

    }


    // Get the AppRegistry class to get the route, middleware, and event related data.
    /**
     * @throws \Exception
     */
    private function getAppRegistry(): void
    {
        $defaultRegistryClassName = '\\NGFramer\\NGFramerPHPBase\\defaults\\AppRegistry';
        $customRegistryClassName = defined('APP_NAMESPACE') ? APP_NAMESPACE . '\\AppRegistry' : null;

        if ($customRegistryClassName && class_exists($customRegistryClassName)) {
            $this->appRegistry = new $customRegistryClassName;
        }

        if (class_exists($defaultRegistryClassName)) {
            $this->appRegistry = new $defaultRegistryClassName;
        } else {
            throw new \Exception("AppRegistry class not found: $defaultRegistryClassName");
        }
    }


	// Run the application by first looking are the request.

    /**
     * @throws \Exception
     */
    public function run(): void
    {
        // Route the request to the controller and get the response
        $this->router->handleRoute();
    }
}