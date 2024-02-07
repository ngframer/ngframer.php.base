<?php

namespace NGFramer\NGFramerPHPBase\exceptions;
class NotFoundException extends \Exception
{
	protected $code = 404;
	protected $message = "The content you are looking for could not be found.";

}