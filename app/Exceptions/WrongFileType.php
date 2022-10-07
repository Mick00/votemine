<?php


namespace App\Exceptions;


use Throwable;

class WrongFileType extends \Exception
{
    public function __construct($acceptedTypes)
    {
        parent::__construct("Accepted types are:".join(",",$acceptedTypes));
    }
}
