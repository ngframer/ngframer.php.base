<?php

namespace NGFramer\NGFramerPHPBase\model;

class _Rules
{
    // The rules for the data to be inserted or passed to database or api respectively.


    // Rules relating to the DataType.
    public const dataType_integer = 'RULE_dataType_integer';
    public const dataType_decimal = 'RULE_dataType_decimal';
    public const dataType_float = 'RULE_dataType_float';
    public const dataType_real = 'RULE_dataType_real';
    public const dataType_string = 'RULE_dataType_string';
    public const dataType_boolean = 'RULE_dataType_boolean';
    public const dataType_date = 'RULE_dataType_date';
    public const dataType_dateTime = 'RULE_dataType_dateTime';
    public const dataType_timeStamp = 'RULE_dataType_timeStamp';
    public const dataType_time = 'RULE_dataType_time';
    public const dataType_year = 'RULE_dataType_year';
    public const dataType_json = 'RULE_dataType_json';


    // Rules relating to the requirements.
    public const required = 'RULE_required';
    public const nullable = 'RULE_nullable';
    public const notNullable = 'RULE_notNullable';


    // Rules relating to the date and time.
    public const isFuture = 'RULE_isFuture';
    public const isPast = 'RULE_isPast';


    // Rules relating to data attributes.
    public const dataLength = 'RULE_dataLength';
    public const unique = 'RULE_unique';
    public const autoIncrement = 'RULE_autoIncrement';


    // Rules relating to the URL and URI.
    public const validUrl = 'RULE_validUrl';
    public const validPath = 'RULE_validPath';


    // Rules relating to the password.
    public const minLength = 'RULE_minLength';
    public const strengthLevel = 'RULE_strengthLevel';


    // Rules relating to the Email.
    public const validEmail = 'RULE_validEmail';
}