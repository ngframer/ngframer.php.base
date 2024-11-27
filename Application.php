<?php

namespace NGFramer\NGFramerPHPBase;

use App\Config\ApplicationConfig;
use NGFramer\NGFramerPHPBase\Controller\Controller;
use NGFramer\NGFramerPHPBase\Defaults\Exceptions\CallbackException;
use NGFramer\NGFramerPHPBase\Defaults\Exceptions\FileException;
use NGFramer\NGFramerPHPBase\Defaults\Exceptions\MiddlewareException;
use NGFramer\NGFramerPHPBase\Defaults\Exceptions\RegistryException;
use NGFramer\NGFramerPHPBase\Response\Response;

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
        // Save the class instance to a static variable.
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
        // Get the root directory path and application type.
        $root = ApplicationConfig::get('root');
        $appType = ApplicationConfig::get('appType');

        if ($appType == 'app') {
            if (file_exists($root . '/Registry/AppRegistry.php')) {
                require_once $root . '/Registry/AppRegistry.php';
            }
        } elseif ($appType == 'api') {
            if (file_exists($root . '/Registry/ApiRegistry.php')) {
                require_once $root . '/Registry/ApiRegistry.php';
            }
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
