<?php

namespace NGFramer\NGFramerPHPBase\controller;

use app\config\ApplicationConfig;
use NGFramer\NGFramerPHPBase\Application;
use NGFramer\NGFramerPHPBase\response\Response;

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
     * Constructor for the controller base class.
     */
    public function __construct()
    {
        $this->application = Application::$application;
        $this->response = Application::$application->response;
    }


    /**
     * Redirect function for controller.
     * Redirects to a given hard-coded URL.
     *
     * @param string $url . Only pass a hard-coded URL. For example, https://www.example.com.
     * @return void
     */
    public function redirect(string $url): void
    {
        $this->application->response->redirect($url);
    }


    /**
     * getBody function for controller.
     * @return array
     */
    public function getBody(): array
    {
        return $this->application->request->getBody();
    }


    /**
     * getRawBody function for controller.
     *
     * @return array
     */
    public function getRawBody(): array
    {
        return (array)json_decode(file_get_contents("php://input"), true);
    }


    /**
     * getMethod function for controller.
     * @return string
     */
    public function getMethod(): string
    {
        return $this->application->request->getMethod();
    }


    /**
     * isMethodGet function for controller.
     * @return bool
     */
    public function isMethodGet(): bool
    {
        return $this->application->request->isMethodGet();
    }


    /**
     * isMethodPost function for controller.
     * @return bool
     */
    public function isMethodPost(): bool
    {
        return $this->application->request->isMethodPost();
    }
}