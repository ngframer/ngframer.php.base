<?php

namespace NGFramer\NGFramerPHPBase\exceptions;
class BadRequestException extends \Exception
{
	protected $code = 400;
	protected $message = "The request is invalid.";
}