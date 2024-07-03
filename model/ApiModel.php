<?php

namespace NGFramer\NGFramerPHPBase\model;

abstract class ApiModel extends BaseModel
{
    // Structural properties of the API input or response.
    protected array $structure = [];

    // All the fields in the API input or response.
    protected array $fields;

    // For insertion.
    // Fields inserted automatically by system.
    protected array $autoFilledSys;

    // For updation.
    // Many at once assignable fields.
    protected array $massFillable;
    // Once at once assignable fields.
    protected array $singleFillable;

    // For update queries.
    // Fields updated automatically by system.
    protected array $autoUpdateSys;
}