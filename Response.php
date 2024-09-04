<?php

namespace NGFramer\NGFramerPHPBase;

class Response
{
    /**
     * Set the status code of the response.
     * @param int $statusCode
     * @return void
     */
	public function setStatusCode(int $statusCode): void
	{
		http_response_code($statusCode);
	}


    /**
     * Render the view with the layout.
     * @param $layoutView
     * @param $contentView
     * @param array $contentParam
     * @return array|string
     */
	public function renderView($layoutView, $contentView, array $contentParam = []): array|string
    {
		// Load the layout data and the content data.
		$layoutData = $this->loadLayoutData($layoutView, $contentParam);
		$contentData = $this->loadContentData($contentView, $contentParam);
		// Replace the {{content}} with the $contentData to create a view.
		return str_replace('{{content}}', $contentData, $layoutData);
	}


    /**
     * Load the layout data.
     * @param $layoutView
     * @param array $contentParam
     * @return bool|string
     */
	protected function loadLayoutData($layoutView, array $contentParam = []): bool|string
    {
		extract($contentParam);
		ob_start();
		include_once ROOT . "/views/layouts/$layoutView.php";
		return ob_get_clean();
	}


    /**
     * Load the content data.
     * @param $contentView
     * @param $contentParam
     * @return bool|string
     */
	protected function loadContentData($contentView, $contentParam = []): bool|string
    {
		extract($contentParam);
		ob_start();
		include_once ROOT . "/views/$contentView.php";
		return ob_get_clean();
	}


    /**
     * Render the API response.
     * @param $dataContent
     * @return string
     */
    public function renderApi($dataContent): string
    {
        header('Content-Type: application/json');
        return json_encode($dataContent);
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