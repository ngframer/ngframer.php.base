<?php

namespace NGFramer\NGFramerPHPBase;

use app\config\ApplicationConfig;
use NGFramer\NGFramerPHPBase\controller\Controller;
use NGFramer\NGFramerPHPBase\defaults\exceptions\CallbackException;
use NGFramer\NGFramerPHPBase\defaults\exceptions\FileException;
use NGFramer\NGFramerPHPBase\defaults\exceptions\MiddlewareException;
use NGFramer\NGFramerPHPBase\defaults\exceptions\RegistryException;

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
        self::$application = $this;
        $this->request = new Request();
        $this->router = new Router($this, $this->request);
        $this->controller = new Controller($this);
        $this->session = new Session();
        $this->response = new Response();
        // Get all the routes, middlewares, and events.
        $this->getRegistry();
    }


    /**
     * Get the default AppRegistry data and developer's AppRegistry data.
     * @throws FileException
     */
    private function getRegistry(): void
    {
        $root = ApplicationConfig::get('root');
        // Check if the default Registry.php file exists.
        if (!file_exists($root . '/vendor/ngframer/ngframer.php.base/defaults/Registry.php')) {
            throw new FileException('Registry.php file not found.', 1001001);
        } else {
            require_once $root . '/vendor/ngframer/ngframer.php.base/defaults/Registry.php';
        }

        // Check if the custom AppRegistry.php file exists in the root directory.
        if (file_exists($root . '/Registry.php')) {
            require_once $root . '/Registry.php';
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
