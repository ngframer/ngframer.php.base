<?php

namespace NGFramer\NGFramerPHPBase\utilities;

class UtilNeupId
{
    /**
     * Private constructor to prevent instantiation.
     */
    private function __construct()
    {
    }


    /**
     * Check if the provided neupId is valid.
     * @param $neupId
     * @return bool
     */
    public static function isValidNeupId($neupId): bool
    {
        // Regular expression pattern to match letters (upper and lower case) and numbers.
        if (preg_match('/^[a-zA-Z0-9]+$/', $neupId)) {
            return true;
        }
        return false;
    }


    /**
     * Check if the provided neupId is reserved for system use.
     * @param $askedNeupId
     * @return bool
     */
    public static function isReservedNeupId($askedNeupId): bool
    {
        $reservedNeupId = ['settings', 'neup', 'profile', 'username', 'example', 'home', 'me', 'neupgroup', 'chat', 'messages', 'index'];
        if (in_array($askedNeupId, $reservedNeupId)) {
            return false;
        }
        return true;
    }
}
