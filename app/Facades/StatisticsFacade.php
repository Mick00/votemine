<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class StatisticsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'statistics';
    }
}
