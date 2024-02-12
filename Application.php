<?php

namespace NGFramer\NGFramerPHPBase;

use NGFramer\NGFramerPHPBase\middleware\MiddlewareManager;

class Application
{
	// Initialization of the variables used across the application.
	public static Application $application;
	public Request $request;
	public Router $router;
	public Controller $controller;
    public MiddlewareManager $middlewareManager;
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
        $this->middlewareManager = new MiddlewareManager();
        $this->session = new Session();
        $this->response = new Response();
        // Get all the routes, middlewares, and events.
        $this->appRegistry = new AppRegistry($this);
        $this->getAppRegistry();

    }


    // Get the AppRegistry class to get the route, middleware, and event related data.
    /**
     * @throws \Exception
     */
    private function getAppRegistry(): void
    {
        // Check if the default AppRegistry.php file exists.
        if (!file_exists(ROOT . '/vendor/ngframer/ngframer.php.base/AppRegistry.php')) {
            throw new \Exception('AppRegistry.php file not found.');
        } else {
            require_once ROOT . '/vendor/ngframer/ngframer.php.base/AppRegistry.php';
        }

        // Check if the custom AppRegistry.php file exists in the root directory.
        if (file_exists(ROOT . '/AppRegistry.php')) {
            require_once ROOT . '/AppRegistry.php';
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