<?php

namespace App\Helpers;

use App\Exceptions\AppException;
use App\Helpers\UtilHelper\ArrayUtilHelper;

class FieldsValidationHelper
{
    /**
     * Checks if the required fields are present in the source.
     *
     * @throws AppException
     */
    public static function validateRequired(array $fields, array $source): void
    {
        if (ArrayUtilHelper::isArrayAssociative($fields)) {
            foreach ($fields as $field => $fieldType) {
                if (!isset($source[$field])) {
                    throw new AppException("Missing required field $field error.", 1021001, 'app.missingField');
                }
                self::typeValidation($fieldType, $source[$field]);
            }
        } else {
            foreach ($fields as $field) {
                if (!isset($source[$field])) {
                    throw new AppException("Missing required field $field error.", 1021002, 'app.missingField2');
                }
            }
        }
    }


    /**
     * Validate the type of the field against the field value.
     *
     * @throws AppException
     */
    private static function typeValidation(string $fieldType, mixed $fieldValue): void
    {
        switch ($fieldType) {
            case 'string':
                if (!is_string($fieldValue)) throw new AppException('Invalid field value type.', 1021003, 'app.invalidValueType');
                break;
            case 'int':
                if (!is_int($fieldValue)) throw new AppException('Invalid field value type.', 1021004, 'app.invalidValueType2');
                break;
            case 'bool':
                if (!is_bool($fieldValue)) throw new AppException('Invalid field value type.', 1021005, 'app.invalidValueType3');
                break;
            case 'array':
                if (!is_array($fieldValue)) throw new AppException('Invalid field value type.', 1021006, 'app.invalidValueType4');
                break;
        }
    }
}
