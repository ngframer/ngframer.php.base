<?php

namespace NGFramer\NGFramerPHPBase\exceptions;
class ForbiddenException extends \Exception
{
    protected $code = 403;
    protected $message = "Permission denied. You don't have permission to access this page.";

}