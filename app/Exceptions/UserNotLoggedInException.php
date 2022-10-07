<?php


namespace App\Exceptions;


use Throwable;

class UserNotLoggedInException extends \Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct("User must be logged in", $code, $previous);
    }
}
