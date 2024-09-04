<?php

namespace NGFramer\NGFramerPHPBase\utilities;


final class UtilCommon
{
    /**
     * Private constructor to prevent instantiation.
     */
    private function __construct()
    {
    }


    /**
     * Function to sanitize the values.
     * Sanitizes data by converting special characters into their HTML entities and returns HTML entities converted data.
     *
     * @param $data
     * @return string
     */
    public static function sanitize($data): string
    {
        // Sanitize the (string)data and return it.
        return htmlspecialchars($data);
    }


    /**
     * Removes leading, trailing and consecutive whitespace from string.
     * @param $data
     * @return array|string|null
     */
    public static function cleanTrim($data): array|string|null
    {
        // Trim the string to remove leading and trailing whitespace.
        $data = trim($data);
        // Replace any sequences of consecutive whitespace characters with a single space character.
        // Return the final trimmed data.
        return preg_replace('/\s+/', ' ', $data);
    }


    /**
     * Function to check if the data is empty.
     * @param $data
     * @return bool
     */
    public static function isOnlyWhiteSpace($data): bool
    {
        // Loop through the string(data) and check if each character is a whitespace character.
        for ($i = 0; $i < strlen($data); $i++) {
            // If any character is not a whitespace character, return False.
            if (!ctype_space($data[$i])) {
                return false;
            }
        }
        // Check was successful and all characters in the string(data) are whitespace characters, so return True.
        return true;
    }


    /**
     * Function to check if the data is only a number.
     * @param $data
     * @return bool
     */
    public static function isOnlyNumber($data): bool
    {
        // Return if the data is numeric in nature.
        return is_numeric($data);
    }


    /**
     * Function to merge multiple arrays into one array.
     * @param ...$arrays
     * @return array
     */
    public static function mergeArray(...$arrays): array
    {
        $mergedArray = [];
        // Iterate through each input array
        foreach ($arrays as $array) {
            // Iterate through the keys of the current array
            foreach (array_keys($array) as $key) {
                // Get the current value in the merged array (if exists)
                $value = $mergedArray[$key] ?? [];
                // Get the value from the current array
                $newValue = $array[$key] ?? [];

                // Handle merging based on the types of values
                if (is_array($value) && is_array($newValue)) {
                    $mergedArray[$key] = array_values(array_unique(array_merge($value, $newValue), SORT_REGULAR));
                } elseif (is_array($value) && !is_array($newValue)) {
                    $mergedArray[$key] = array_values(array_unique(array_merge($value, [$newValue]), SORT_REGULAR));
                } elseif (!is_array($value) && is_array($newValue)) {
                    $mergedArray[$key] = array_values(array_unique(array_merge([$value], $newValue), SORT_REGULAR));
                } else {
                    $mergedArray[$key] = array_values(array_unique([$value, $newValue], SORT_REGULAR));
                }

                // Handle merging for nested arrays
                if (is_array($value) && is_array($newValue)) {
                    foreach ($value as $innerKey => $innerValue) {
                        if (is_array($innerValue) && isset($newValue[$innerKey]) && is_array($newValue[$innerKey])) {
                            // Recursively merge nested arrays
                            $mergedArray[$key][$innerKey] = self::mergeArray($mergedArray[$key][$innerKey] ?? [], $newValue[$innerKey]);
                        }
                    }
                }
            }
        }
        return $mergedArray;
    }


    /**
     * Function to extract all the values from the array.
     * @param array &$array
     * @param $criteria
     * @param bool $unsetByKey
     * @return void
     */
    public static function deleteArrayData(array &$array, $criteria, bool $unsetByKey = true): void
    {
        $originalKeys = array_keys($array); // Store original keys to maintain their order

        foreach ($array as $key => $value) {
            if (($unsetByKey && in_array($key, $criteria)) || (!$unsetByKey && in_array($value, $criteria))) {
                unset($array[$key]);
            }
        }

        // Reindex the array to ensure keys are in consecutive integer order
        $array = array_values($array);

        // Reapply original keys to remaining elements
        foreach ($array as $index => $value) {
            $array[$originalKeys[$index]] = $value;
            if ($originalKeys[$index] !== $index) {
                unset($array[$index]);
            }
        }
    }


    /**
     * Function to check if the array is associative or not.
     * @param array $array
     * @return bool
     */
    public static function isAssociativeArray(array $array): bool
    {
        if ([] === $array) return false;
        return array_keys($array) !== range(0, count($array) - 1);
    }


    /**
     * Convert multiple arguments to a single array.
     * @param ...$args
     * @return array
     */
    public static function makeArray(...$args): array
    {
        $result = [];
        foreach ($args as $arg) {
            if (is_array($arg)) {
                $result = array_merge($result, $arg);
            } elseif (is_string($arg)) {
                if (!empty($arg)) {
                    $result[] = $arg;
                }
            }
        }
        return $result;
    }


    /**
     * Extract all values from the array.
     * @param ...$args
     * @return array . Returns all values as flat array.
     */
    public static function extractArrayAllLevel(...$args): array
    {
        $result = [];
        foreach ($args as $arg) {
            if (!is_array($arg)) {
                $result[] = $arg;
            } else {
                $result = array_merge($result, UtilCommon::extractArrayAllLevel(...$arg));
            }
        }
        return $result;
    }


    /**
     * Function to remove repetition from the array.
     * @param array $array
     * @return array
     */
    public static function removeRepetitionFromArray(array $array): array
    {
        return array_values(array_unique($array));
    }
}