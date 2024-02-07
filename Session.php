<?php

namespace NGFramer\NGFramerPHPBase;

class Session
{
    public function __construct()
    {
        session_start();
    }



    public function set($initial, $key, $value): void
    {
        if (!empty($initial)){
            $_SESSION[$initial][$key] = $value;
        }else{
            $_SESSION[$key] = $value;
        }
    }


    public function get($initial, $key): string|array|int|bool|null
    {
        return $_SESSION[$initial][$key] ?? null;
    }



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



    public function setFlash($key, $value): void
    {
        $this->set('flash', $key, $value);
    }


    public function getFlash($key)
    {
        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $value;
        }
        return null;
    }

    public function __destruct()
    {
        if (isset($_SESSION['flash'])) unset($_SESSION['flash']);
    }
}