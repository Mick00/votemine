<?php


namespace App\Exceptions;


use Throwable;

class FetchFailException extends \Exception
{
    public function __construct($url)
    {
        parent::__construct("Url: ".$url." unreachable");
    }
}
