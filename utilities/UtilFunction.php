<?php

namespace NGFramer\NGFramerPHPBase\utilities;

use Exception;
use NGFramer\NGFramerPHPBase\defaults\exceptions\AppException;
use ReflectionException;
use ReflectionFunction;

final class UtilFunction
{
    /**
     * Private constructor to prevent instantiation.
     */
    private function __construct()
    {
    }


    /**
     * Function to get the name of a function from a closure.
     * @param $closure
     * @return string
     * @throws AppException
     */
    public static function getFunctionNameFromClosure($closure): string
    {
        // Using reflection to extract information about the closure
        // Attempt to get the name of the function (note: may not always work as expected)
        try {
            $reflection = new ReflectionFunction($closure);
            return $reflection->getName();
        } catch (ReflectionException $e) {
            throw new AppException("Error in getting function name from closure : " . $e->getMessage(), 1005001);
        }
    }


    /**
     * Function to extract function name and field names from a string.
     * @return array{function: string, fields: array}
     * @throws AppException
     */
    public static function extractFuncData($inputString): array
    {
        // Modify the regular expression pattern to match your desired function name format
        $pattern = '/\b(yourFunctionName)\s*\(\s*([\'"]?)([a-zA-Z_][a-zA-Z0-9_]*)\2\s*(,\s*[\'"]?([a-zA-Z_][a-zA-Z0-9_]*)\5)?\s*\)/i';

        // Use preg_match to check if the input string matches the modified pattern
        if (preg_match($pattern, $inputString, $matches)) {
            $functionName = $matches[1];
            $fieldNames = array();

            if (!empty($matches[3])) {
                $fieldNames[] = $matches[3];
            }
            if (!empty($matches[5])) {
                $fieldNames[] = $matches[5];
            }

            return array(
                'function' => $functionName,
                'fields' => $fieldNames,
            );
        } else {
            // Throw an exception if the input string doesn't match the modified format
            throw new AppException("Invalid function format: $inputString", 1005002);
        }
    }
}