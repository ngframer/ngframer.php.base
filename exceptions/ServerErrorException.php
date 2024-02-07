<?php

namespace NGFramer\NGFramerPHPBase\exceptions;
class ServerErrorException extends \Exception
{
    protected $code = 403;
    protected $message = "Server Error";

}