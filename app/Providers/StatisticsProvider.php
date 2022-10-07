<?php

namespace App\Providers;

use App\ServerStatistics;
use Illuminate\Support\ServiceProvider;

class StatisticsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('statistics',function(){
            return new ServerStatistics();
        });
    }
}
