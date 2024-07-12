<?php

namespace NGFramer\NGFramerPHPBase;

use app\config\ApplicationConfig;

class Controller
{
    // Instantiation of application, and middleware.
    public Application $application;


    // Initialization of the Application class for this whole main parent Controller class.
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /*
     * @param $dataContent1. API => dataContent. Web => layoutView.
     * @param $dataContent2. API => passNothing. Web => contentView.
     * @param $dataContent3. API => passNothing. Web => contentParam.
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

    // Render view function for controller. Only for ease of use in Controllers.
    private function renderView($layoutView, $contentView, $contentParam = []): void
    {
        echo $this->application->response->renderView($layoutView, $contentView, $contentParam);
    }

    // Render api function for controller. Only for ease of use in Controllers.
    private function renderApi($dataContent): void
    {
        echo $this->application->response->renderApi($dataContent);
    }


    // Get body function for controller. Only for ease of use in Controllers.
    public function getBody(): array
    {
        return $this->application->request->getBody();
    }


    // Get method function for controller. Only for ease of use in Controllers.
    public function getMethod(): string
    {
        return $this->application->request->getMethod();
    }


    // Is method get function for controller. Only for ease of use in controllers.
    public function isMethodGet(): bool
    {
        return $this->application->request->isMethodGet();
    }


    // Is method post function for controller. Only for ease of use in controllers.
    public function isMethodPost(): bool
    {
        return $this->application->request->isMethodPost();
    }
}
