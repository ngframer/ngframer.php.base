<?php

namespace NGFramer\NGFramerPHPBase\controller;

use app\config\ApplicationConfig;
use NGFramer\NGFramerPHPBase\Application;
use Exception;

class Controller
{
    // Instantiation of application, and middleware.
    public Application $application;


    /**
     * Constructor for the controller base class.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }


    /**
     * Function to render the view or the API response.
     * @param $dataContent1 . API ⇒ dataContent. Web ⇒ layoutView.
     * @param $dataContent2 . API ⇒ passNothing. Web ⇒ contentView.
     * @param $dataContent3 . API ⇒ passNothing. Web ⇒ contentParam.
     * @throws Exception
     */
    public function render($dataContent1 = null, $dataContent2 = null, $dataContent3 = null): void
    {
        $appType = ApplicationConfig::get('appType');
        if ($appType == 'api') {
            $this->renderApi($dataContent1);
        } else if ($appType == 'web') {
            $this->renderView($dataContent1, $dataContent2, $dataContent3);
        } else {
            throw new Exception("The following appType does not exists.");
        }
    }


    /**
     * renderView function for controller.
     * @param $layoutView . API ⇒ passNothing. Web ⇒ layoutView.
     * @param $contentView . API ⇒ passNothing. Web ⇒ contentView.
     * @param array $contentParam . API ⇒ passNothing. Web ⇒ contentParam.
     * @return void
     */
    private function renderView($layoutView, $contentView, array $contentParam = []): void
    {
        echo $this->application->response->renderView($layoutView, $contentView, $contentParam);
    }


    /**
     * renderApi function for controller.
     * @param $dataContent
     * @return void
     */
    private function renderApi($dataContent): void
    {
        echo $this->application->response->renderApi($dataContent);
    }


    /**
     * Redirect function for controller.
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
