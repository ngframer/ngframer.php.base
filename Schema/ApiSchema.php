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
     * Structure of the data format in the API.
     * @var array
     */
    protected array $dataStructure = [];


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
     * @returns array. Returns the custom headers for the API request.
     */
    abstract protected function defineHeaders(): static;


    /**
     * Function to define the structure of the API.
     *
     * @return array. Returns the JSON structure of the API.
     */
    abstract protected function defineData(): static;


    /**
     * Function to define additional headers in the request.
     *
     * @param string $headerName
     * @param string $headerValue
     *
     * @return $this
     */
    final public function setHeader(string $headerName, string $headerValue): static
    {
        $this->structure['header'][$headerName] = $headerValue;
        return $this;
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
        $response = curl_exec($this->curlInstance);

        // Closing the cURL instance.
        curl_close($this->curlInstance);

        // Returning the response.
        if ($response === false) {
            return json_decode(['status' => false], true);
        } else {
            return json_decode(['status' => true, 'response' => $response], true);
        }
    }
}
