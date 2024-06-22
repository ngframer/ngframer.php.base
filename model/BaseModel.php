<?php

namespace NGFramer\NGFramerPHPBase\model;

use Exception;


abstract class BaseModel
{
    // The properties for the Model Classes.
    // Fields array will have the following keys: [rules@array@string, data@mixed, errors@array@string]
    protected array $fields;


    // Get the fields.
    final protected function getFields(): array
    {
        return $this->fields;
    }


    // Get the data related to specified field.
    final protected function getField(string $field): array|null
    {
        return $this->fields[$field] ?? null;
    }


    /**
     *  Get all the rules that exists for a specified field.
     *
     * @throws Exception
     */
    final public function getRules(string $field): array|null
    {
        return $this->fields[$field]['rules'] ?? throw new Exception("Field $field does not exists.");
    }


    final public function setError(string $field, string $error): void
    {
        $this->fields[$field]['errors'][] = $error;
    }


    /**
     * Get the errors for the field asked.
     *
     * @throws Exception
     */
    final public function getErrors(string $field): array
    {
        return $this->fields[$field]['errors'] ?? throw new Exception("Field $field does not exists");
    }

    /**
     * Check if the function has any errors.
     *
     * @throws Exception
     */
    final public function hasErrors(string $field): bool
    {
        if (count($this->getErrors($field)) > 0) {
            return true;
        }
        return false;
    }


    /**
     * Get the first error from the field.
     *
     * @throws Exception
     */
    final public function getFirstError($fieldName): array|null
    {
        return $this->getErrors($fieldName)[0] ?? null;
    }
}