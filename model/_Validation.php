<?php

namespace NGFramer\NGFramerPHPBase\model;

use DateTime;
use Exception;

class _Validation
{
    // Variables for this class.
    public static $instance;


    // The constructor is protected, you can't initiate a class just on itself.
    protected function _construct() { }



    // Constructor function but it's static.
    public static function init(): void
    {
        // Create instance if not exists.
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        // Get the rule and then implement the function here.

    }


    // Functions relating to the validation of Requirements of model.
    public function validate_dataType_integer($data): bool
    {
        return is_int($data);
    }


    public function validate_dataType_decimal($data, $format): bool
    {
        if (is_numeric($data)) {
            list($whole, $point) = explode('.', $data);
            if (strlen($point) <= $format[1] && strlen($whole) <= $format[0]) {
                return true;
            }
        }
        return false;
    }


    public function validate_dataType_float($data): bool
    {
        return is_float($data);
    }


    public function validate_dataType_real($data): bool
    {
        return is_float($data);
    }


    public function validate_dataType_string($data): bool
    {
        return is_string($data);
    }


    public function validate_dataType_boolean($data): bool
    {
        return is_bool($data);
    }


    public function validate_dataType_date($data): bool
    {
        list($month, $day, $year) = explode("-", $data);
        return checkdate($month, $day, $year);
    }


    public function validate_dataType_dateTime($data): bool
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $data) !== false;
    }


    public function validate_dataType_timeStamp($data): bool
    {
        return (is_numeric($data) && (int)$data == $data)
            && ($data <= PHP_INT_MAX)
            && ($data >= ~PHP_INT_MAX);
    }


    public function validate_dataType_time($data): bool
    {
        return DateTime::createFromFormat('H:i:s', $data) !== false;
    }


    public function validate_dataType_year($data): bool
    {
        return DateTime::createFromFormat('Y', $data) !== false;
    }


    public function validate_dataType_json($data): bool
    {
        json_decode($data);
        return json_last_error() === JSON_ERROR_NONE;
    }


    public function validate_required($data): bool
    {
        return !empty($data);
    }

    public function validate_nullable($data): bool
    {
        return true;
    }

    public function validate_notNullable($data): bool
    {
        if ($data != null) return true;
        else return false;
    }


    /**
     * @throws Exception
     */
    public function validate_isFuture($data): bool
    {
        $now = new DateTime();
        $date = new DateTime($data);
        return $date > $now;
    }


    /**
     * @throws Exception
     */
    public function validate_isPast($data): bool
    {
        $now = new DateTime();
        $date = new DateTime($data);
        return $date < $now;
    }


    public function validate_dataLength($data, $length): bool
    {
        return strlen($data) === $length;
    }


    public function validate_unique($data, $otherData): bool
    {
        // To be handled by database.
        return true;
    }


    public function validate_autoIncrement($data): bool
    {
        return $this->validate_dataType_integer($data) && $this->validate_nullable($data);
    }


    public function validate_validUrl($data): bool
    {
        return filter_var($data, FILTER_VALIDATE_URL) !== false;
    }


    /*
     *  Needs to be changed, this does not validate the path but the actual path, needs to be changed.
     *  TODO: Change this function as required.
     */
    public function validate_validPath($data): bool
    {
        return $this->validate_validUrl($data);
    }

    public function validate_minLength($data, $length = 8): bool
    {
        return strlen($data) >= $length;
    }


    public function validate_strengthLevel($data): bool
    {
        // TODO: To be handled by some good functional calculations.
        return true;
    }


    public function validate_validEmail($data): bool
    {
        return filter_var($data, FILTER_VALIDATE_EMAIL) !== false;
    }


    public function validate_validPhone($data): bool
    {
        // TODO: To be handled by some good functional calculations.
        return true;
    }


    public function validate_usernameHarshness($data): bool
    {
        // TODO: To be handled by some good functional calculations.
        return true;
    }
}
