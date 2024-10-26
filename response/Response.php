<?php

namespace NGFramer\NGFramerPHPBase\response;

use Exception;

class Response
{
    /**
     * Set the status code of the response.
     * @param int $statusCode
     * @return void
     */
	public function statusCode(int $statusCode): void
	{
		http_response_code($statusCode);
	}


    /**
     * Function to render some kind of output.
     */
    public function render(): _Render
    {
        return new _Render();
    }


    /**
     * Redirect to the given hard-coded URL.
     * @param string $url
     * @return void
     */
    public function redirect(string $url): void
    {
        header('Location: ' . $url);
    }
}