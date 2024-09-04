<?php

namespace NGFramer\NGFramerPHPBase;

class Request
{

    /**
     * Returns the path the user is trying to access from the URL.
     * @return string
     */
	public function getPath(): string
    {
		$uri = $_SERVER['REQUEST_URI'] ?? '/';
        return parse_url($uri)['path'];
	}


    /**
     * Returns the method the user is trying to access the URL.
     * @return string
     */
	public function getMethod(): string
    {
		return strtolower($_SERVER['REQUEST_METHOD']);
	}


    /**
     * Returns if the method the user is trying to access the URL through is 'get'.
     * @return bool
     */
	public function isMethodGet(): bool
    {
		return $this->getMethod() === 'get';
	}


    /**
     * Returns if the method the user is trying to access the URL through is 'post'.
     * @return bool
     */
	public function isMethodPost(): bool
    {
		return $this->getMethod() === 'post';
	}


    /**
     * Returns the data sent by the user in the request.
     * @return array
     */
	public function getBody(): array
    {
		// Initialize an empty array $data.
		$data = [];

		// Check for the GET method of sending data.
		if ($this->isMethodGet()) {
			foreach ($_GET as $key => $value){
				$data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}			
		// Check for the POST method of sending data.
		elseif($this->isMethodPost()){
			foreach ($_POST as $key => $value){
				$data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}
		// Return the sanitized data.
		return $data;
	}
}
