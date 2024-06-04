<?php

namespace NGFramer\NGFramerPHPBase;

class Controller
{
	// Instantiation of application, and middleware.
	public Application $application;


	// Initialization of the Application class for this whole main parent Controller class.
	public function __construct(Application $application)
	{
        $this->application = $application;
    }

	// Render view function for controller. Only for ease of use in Controllers.
	public function renderView($layoutView, $contentView, $contentParam = []): void
    {
        if (APPMODE == 'development') {
            throw new \Exception('Development mode is not allowed to render views.');
        }
        echo $this->application->response->renderView($layoutView, $contentView, $contentParam);
	}

    public function renderApi($dataContent): void
    {
        if (APPMODE != 'development') {
            throw new \Exception('Only development mode is not allowed to render API\'s.');
        }
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
