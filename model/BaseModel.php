<?php

namespace NGFramer\NGFramerPHPBase\model;

abstract class BaseModel
{
    // Structural properties of the structure.
    protected array $structure = [];

    // All the fields in the structure.
    protected array $fields;
    protected array $insertableFields;
    protected array $updatableFields;
    protected array $massUpdatableFields;
}