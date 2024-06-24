<?php

namespace NGFramer\NGFramerPHPBase\model;
abstract class ApiModel extends BaseModel
{
    // Structure contains the name, type, and other details like path of the api.
    protected array $structure;

    // The $fields contains all the properties that exists in the model.
    protected array $fields;
}