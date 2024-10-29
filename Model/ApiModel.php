<?php

namespace NGFramer\NGFramerPHPBase\model;

abstract class ApiModel extends BaseModel
{
    // Structural properties of the API input or response.
    protected array $structure = [];

    // All the fields in the API.
    protected array $fields;
    protected array $insertableFields;
    protected array $updatableFields;
    protected array $massUpdatableFields;
}