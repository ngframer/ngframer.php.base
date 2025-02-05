<?php

namespace NGFramer\NGFramerPHPBase\Schema;

use NGFramer\NGFramerPHPBase\Defaults\Exceptions\SchemaException;

abstract class ApiSchema
{
    /**
     * Structural properties of the API.
     *
     * @var array
     */
    protected array $structure = [];

    
    /**
     * All the fields that can be sent in the API request.
     *
     * @var array
     */
    protected array $requestParameters = [];

    
    /**
     * All the fields that are required in the API request.
     *
     * @var array
     */
    protected array $requiredRequestParameters = [];

    
    /**
     * All the fields that will be returned if request is approved.
     *
     * @var array
     */
    protected array $responseParameters = [];


    /**
     * Instances of the class.
     *
     * @var array|null
     */
    protected static ?array $instances = null;


    /**
     * Instance of the cURL.
     */
    protected $curlInstance;


    /**
     * Execution response of the cURL.
     * @var mixed
     */
    protected mix $curlRequestResponse;


    /**
     * Constructor of the class.
     */
    final protected function __construct()
    {
        $this->curlInstance = curl_init();
    }


    /**
     * Function to initialize the instance of the class.
     *
     * @return static. Returns an instance of this class.
     */
    final public static function init(): static
    {
        $calledClass = static::class;
        if (!isset(self::$instances[$calledClass])) {
            self::$instances[$calledClass] = new static();
        }
        return self::$instances[$calledClass];
    }


    /**
     * Function to define custom herader for the API request.
     *
     * @returns static. Returns the custom headers for the API request.
     */
    final public function defineHeader(string $title, string $value): static
    {
        $this->structure['headers'][$title] = $value;
        return $this;
    }


    /**
     * Function to define the structure of the API.
     *
     * @return array. Returns the JSON structure of the API.
     */
    final public function defineData(string $title, string $value): static
    {
        $this->structure['data'][$title] = $value;
    }


    /**
     * Function to set the request method for the API.
     *
     * @param string $method
     * @return $this
     *
     * @throws SchemaException
     */
    final public function setMethod(string $method): static
    {
        // Convert the method name to upper case.
        $method = strtoupper($method);
        // Check if the method is a valid method.
        if (!in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
            throw new SchemaException('Invalid method passed', 0, 'apiSchema.invalidMethod', null, 500, []);
        }
        // Set the method in the structure.
        $this->structure['method'] = $method;
        return $this;
    }


    /**
     * Function to send a request.
     *
     * @return array. Returns the response of the request.
     */
    public function sendRequest(): array
    {
        // Sending the request by setting variables.
        // Return the response instead of outputting it.
        curl_setopt($this->curlInstance, CURLOPT_RETURNTRANSFER, true);

        // Setting the URL and the request.
        curl_setopt($this->curlInstance, CURLOPT_URL, $this->structure['endpoint']);

        // Setting the headers for the cURL instance.
        curl_setopt($this->curlInstance, CURLOPT_HTTPHEADER, $this->structure['headers']);

        // Setting the request method for the cURL instance.
        // TODO: Add support for other methods like put, patch, delete, etc.
        if ($this->structure['method'] === 'GET') {
            curl_setopt($this->curlInstance, CURLOPT_HTTPGET, true);
        } elseif ($this->structure['method'] === 'POST') {
            curl_setopt($this->curlInstance, CURLOPT_POST, true);
            curl_setopt($this->curlInstance, CURLOPT_POSTFIELDS, $this->structure['request']);
        }

        // Executing the cURL instance.
        $this->curlRequestResponse['output'] = curl_exec($this->curlInstance);
        $this->curlRequestResponse['statusCode'] = curl_getinfo($this->curlInstance, CURLINFO_HTTP_CODE);

        // Closing the cURL instance.
        curl_close($this->curlInstance);
    }


    /**
     * Function to get the response of the request.
     *
     * @return mixed. Returns the response of the request.
     */
    public function getResponse(): mixed
    {
        return $this->curlRequestResponse['output'];
    }


    /**
     * Function to get the status code of the request.
     *
     * @return int. Returns the status code of the request.
     */
    public function getStatusCode(): int
    {
        return $this->curlRequestResponse['statusCode'];
    }
}
