<?php

namespace NGFramer\NGFramerPHPBase\utilities;

final class UtilGender
{
    /**
     * Private constructor to prevent instantiation.
     */
    private function __construct()
    {
    }


    /**
     * Check if the gender is valid.
     * @param $gender
     * @return bool
     */
    public static function isValidGender($gender): bool
    {
        // Only 4 gender types are valid.
        if ($gender == "male" || $gender == "female" || $gender == "custom" || $gender == "undisclosed") {
            return true;
        } else {
            return false;
        }
    }
}
