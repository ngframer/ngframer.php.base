<?php

namespace NGFramer\NGFramerPHPBase;

class Session
{
    /**
     * Session constructor.
     */
    public function __construct()
    {
        session_start();
    }


    /**
     * Function to set session key and value with an initial.
     * @param string $initial
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $initial, string $key, mixed $value): void
    {
        if (!empty($initial)){
            $_SESSION[$initial][$key] = $value;
        }else{
            $_SESSION[$key] = $value;
        }
    }


    /**
     * Function to get session value with an initial and key.
     * @param string $initial
     * @param string $key
     * @return mixed
     */
    public function get(string $initial, string $key): mixed
    {
        return $_SESSION[$initial][$key] ?? null;
    }


    /**
     * Function to remove session variable with an initial and key.
     * @param $initial
     * @param $key
     * @return void
     */
    public function remove($initial, $key): void
    {
        if (empty($initial)) {
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
            }
        } else {
            if (isset($_SESSION[$initial][$key])) {
                unset($_SESSION[$initial][$key]);
            }
        }
    }


    /**
     * Function to set a flash variable in session.
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setFlash(string $key, mixed $value): void
    {
        $this->set('flash', $key, $value);
    }


    /**
     * Function to get the value of a flash variable from the session.
     * @param $key
     * @return mixed|null
     */
    public function getFlash($key): mixed
    {
        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $value;
        }
        return null;
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