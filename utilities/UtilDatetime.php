<?php

namespace NGFramer\NGFramerPHPBase\utilities;

use DateTime;
use Exception;
use NGFramer\NGFramerPHPBase\defaults\exceptions\DateTimeException;

final class UtilDatetime
{
    /**
     * Private constructor to prevent instantiation.
     */
    private function __construct()
    {
    }


    /**
     * Check if the given date is valid.
     * @param $date
     * @return bool
     */
    public static function isValidDate($date): bool
    {
        // Specify the expected date format.
        $format = 'Y-m-d';
        // Create a DateTime object from the given date string.
        $dateTime = DateTime::createFromFormat($format, $date);
        // Check if the DateTime object was successfully created and if its formatted value matches the original date string.
        // If both conditions are true, the date is considered valid
        return (bool)$dateTime && $dateTime->format($format) === $date;
    }


    /**
     * Check if the given date is valid and in the past.
     * @param $date
     * @return bool
     */
    public static function isValidBirthdate($date): bool
    {
        $currentDate = new DateTime();
        if (UtilDatetime::isValidDate($date)) {
            $providedDate = DateTime::createFromFormat('Y-m-d', $date);
            if ($providedDate && $providedDate <= $currentDate) {
                return true;
            }
        }
        return false;
    }


    /**
     * Calculate age in decimal based on birthdate.
     * @param $birthdate
     * @return float
     * @throws DateTimeException
     */
    public static function calculateAge($birthdate): float
    {
        if (UtilDatetime::isValidDate($birthdate)) {
            $birthdate = DateTime::createFromFormat('Y-m-d', $birthdate);
            $currentDate = new DateTime();
            $diff = $currentDate->diff($birthdate);
            return round($diff->y + ($$diff->m / 12), 3);
        }
        throw new DateTimeException('Invalid birthdate. Either the date is not valid or the date is in the future.', 1004001);
    }


    /**
     * Check the validity of the age.
     * @param $birthdate
     * @return bool
     * @throws Exception
     */
    public static function isValidAge($birthdate): bool
    {
        if (UtilDatetime::isValidDate($birthdate)) {
            $age = UtilDatetime::calculateAge($birthdate);
            if (($age > 0) && ($age < 120)) return true;
            else return false;
        } else return false;
    }


    /**
     * Check if the age is fine for an Individual NeupID type.
     * @param $birthdate
     * @return bool
     * @throws Exception
     */
    public static function isValidIndivAge($birthdate): bool
    {
        if (UtilDatetime::isValidAge($birthdate)) {
            $age = UtilDatetime::calculateAge($birthdate);
            if (UtilDatetime::isValidAge($birthdate) && ($age >= 16)) return true;
            else return false;
        } else return false;
    }


    // Check if the age is fine for the Individual NeupID type.

    /**
     * Check if the age is fine for a Dependent NeupID type.
     * @param $birthdate
     * @return bool
     * @throws Exception
     */
    public static function isValidDependentAge($birthdate): bool
    {
        if (UtilDatetime::isValidAge($birthdate)) {
            $age = UtilDatetime::calculateAge($birthdate);
            if (UtilDatetime::isValidAge($birthdate) && ($age < 16)) return true;
            else return false;
        } else return false;
    }


    // Handling birthdate for certain countries like:


}
