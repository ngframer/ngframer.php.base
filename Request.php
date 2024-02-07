<?php

namespace NGFramer\NGFramerPHPBase;

class Request
{

	// Returns the path, the user is trying to access.
	public function getPath()
	{
		$uri = $_SERVER['REQUEST_URI'] ?? '/';
        return parse_url($uri)['path'];
	}

	// Returns the method the User is trying to access.
	public function getMethod(): string
    {
		return strtolower($_SERVER['REQUEST_METHOD']);
	}


	// Returns if the method the User is trying to access is get.
	public function isMethodGet(): bool
    {
		return $this->getMethod() === 'get';
	}

	// Returns if the method the User is trying to access is post.
	public function isMethodPost(): bool
    {
		return $this->getMethod() === 'post';
	}


	// Returns the data the user is trying to pass us.
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
