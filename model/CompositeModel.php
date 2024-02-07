<?php

namespace NGFramer\NGFramerPHPBase\model;
abstract class CompositeModel extends BaseModel
{
    // Properties to be extended by ModelAdvance Classes.
    protected array $fields;
    protected array $data;
    protected array $rules;
    protected array $errors;
}
