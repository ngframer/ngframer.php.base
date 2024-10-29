<?php

namespace NGFramer\NGFramerPHPBase;

class Request
{
    /**
     * Returns the path the user is trying to access from the URL.
     *
     * @return string
     */
    public function getPath(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return parse_url($uri)['path'];
    }


    /**
     * Returns the method the user is trying to access the URL.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }


    /**
     * Returns the header data sent in the request.
     *
     * @return array
     */
    public function getHeader(): array
    {
        // Get the headers from the apache_request_headers function.
        $result = apache_request_headers();

        // Check result and return the headers.
        if ($result == false) return [];
        return $result;
    }


    /**
     * Returns the data sent in the request.
     *
     * @return array
     */
    public function getBody(): array
    {
        // Initialize array to save data and get method of sending data.
        $method = $this->getMethod();
        $data = [];

        // Check for the requst method of sending data and fetch them.
        if ($method === 'get') {
            foreach ($_GET as $key => $value) {
                $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        } elseif ($method === 'post') {
            foreach ($_POST as $key => $value) {
                $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        // Return the sanitized data.
        return $data;
    }


    /**
     * Returns the raw body data sent in the request.
     *
     * @return array
     */
    public function getRawBody(): array
    {
        // Get the raw body data from the php://input stream.
        return (array)json_decode(file_get_contents("php://input"), true);
    }
}