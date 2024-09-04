<?php

namespace NGFramer\NGFramerPHPBase\utilities;

final class UtilEmail
{
    /**
     * Private constructor to prevent instantiation.
     */
    private function __construct()
    {
    }


    /**
     * Check if the email is valid
     * @param $email
     * @return bool
     */
    public static function isValidEmail($email): bool
    {
        // Using filter_var function with FILTER_VALIDATE_EMAIL flag
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
