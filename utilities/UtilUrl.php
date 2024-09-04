<?php

namespace NGFramer\NGFramerPHPBase\utilities;

final class UtilUrl
{
    /**
     * Private constructor to prevent instantiation
     */
    private function __construct()
    {
    }


    /**
     * Checks if the input is a valid URL.
     * @param $url
     * @return bool
     */
    public static function isValidUrl($url): bool
    {
        return (bool)filter_var($url, FILTER_VALIDATE_URL);
    }
}
