<?php

namespace NGFramer\NGFramerPHPBase;

use app\config\ApplicationConfig;
use NGFramer\NGFramerPHPBase\controller\Controller;
use NGFramer\NGFramerPHPBase\defaults\exceptions\CallbackException;
use NGFramer\NGFramerPHPBase\defaults\exceptions\FileException;
use NGFramer\NGFramerPHPBase\defaults\exceptions\MiddlewareException;
use NGFramer\NGFramerPHPBase\defaults\exceptions\RegistryException;
use NGFramer\NGFramerPHPBase\response\Response;

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

    /**
     * Application constructor.
     * @throws FileException
     */
    public function __construct()
    {
        // Save the classe instance to a static variable.
        self::$application = $this;
        // Just get the path value and more, no need of dependencies.
        $this->request = new Request();
        // Router's constructor will need Application instance and request instance.
        $this->router = new Router();
        // Response's constructor will need nothing.
        $this->response = new Response();
        // Controller's constructor will need Application instance and response instance.
        $this->controller = new Controller();
        // Session's constructor will need nothing.
        $this->session = new Session();
        // Get all the routes, middlewares, and events.
        $this->getRegistry();
    }


    /**
     * Get the default AppRegistry data and developer's AppRegistry data.
     * @throws FileException
     */
    private function getRegistry(): void
    {
        // Get the root directory path.
        $root = ApplicationConfig::get('root');

        // Check if Registry.php exists in the root directory.
        if (file_exists($root . '/Registry.php')) {
            require_once $root . '/Registry.php';
        } else {
            throw new FileException('Registry.php file not found.', 1001003, 'base.file.registry.notFound.3');
        }
    }


    /**
     * Function to handle the request and route it to the controller.
     * @throws MiddlewareException
     * @throws CallbackException
     * @throws RegistryException
     */
    public function run(): void
    {
        // Route the request to the controller and get the response
        $this->router->handleRoute();
    }
}
