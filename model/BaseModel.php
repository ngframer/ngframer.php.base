<?php

namespace NGFramer\NGFramerPHPBase\model;

abstract class BaseModel
{
    // Structure contains the name, type, and other details of the model (type) classes.
    protected array $structure;

    // The $fields contains all the properties that exists in the model.
    protected array $fields;
    // Only the fields that are not allowed to be passed to the model.
    protected array $fillableFields;
    // Only the fields that are automatically filled by the model (database or api).
    protected array $autofillFields;
    // Only the fields that are hidden from the general view, and are not supposed to be updated manually, but systematically is allowed like the last_updated column.
    protected array $guardedFields;
}