<?php

namespace NGFramer\NGFramerPHPBase;

use NGFramer\NGFramerPHPBase\Defaults\Exceptions\SessionException;

class Session
{
    /**
     * Instance of the Session class.
     * @var Session
     */
    private static Session $session;



    /**
     * Session configuration.
     * @var array
     **/
    private array $sessionConfig = [];


    /**
     * Instantiate for Render.
     * Use the same object all the time
     */
    public static function init(): static
    {
        if (empty(self::$session)) {
            self::$session = new self();
        }
        return self::$session;
    }


    /**
     * Private constructor to prevent instantiation.
     */
    private function __construct()
    {
        session_start();
    }


    /**
     * Function to set Session name.
     *
     * @param string $name
     * @return void
     */
    public function name(string $name): void
    {
        $this->sessionConfig['name'] = $name;
    }


    /**
     * Function to set Session value.
     *
     * @param mixed $value
     * @return void
     */
    public function value(mixed $value): void
    {
        $this->sessionConfig['value'] = $value;
    }


    /**
     * Function to set the Session.
     *
     * @return void
     * @throws SessionException
     */
    public function set(): void
    {
        // Check for the name and value of the session.
        if (empty($this->sessionConfig['name'])) {
            throw new SessionException('Session name is required.', 0, 'base.session.nameRequired', null, 500);
        }
        if (empty($this->sessionConfig['value'])) {
            throw new SessionException('Session value is required.', 0, 'base.session.valueRequired', null, 500);
        }

        // Set Session value based on the data provided above.
        $_SESSION[$this->sessionConfig['name']] = $this->sessionConfig['value'];
    }


    /**
     * Function to get the Session.
     *
     * @return mixed
     */
    public function get(): mixed
    {
        return $_SESSION[$this->sessionConfig['name']] ?? null;
    }


    /**
     * Function to delete the session.
     *
     * @return void
     */
    public function delete(): void
    {
        if (isset($_SESSION[$this->sessionConfig['name']])) {
            unset($_SESSION[$this->sessionConfig['name']]);
        }
    }



    /**
     * Function to destroy session.
     * @return void
     */
    public function __destruct()
    {
        if (isset($_SESSION['flash'])) unset($_SESSION['flash']);
    }
}