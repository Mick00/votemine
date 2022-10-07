<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/v1')
    ->namespace('Api')
    ->name('api')
    ->middleware('auth:sanctum')
    ->group(function (){
        Route::get('whoami', 'IdentityController@whoami');
        Route::prefix('myserver')
            ->name('.server')
            ->group(function (){
                Route::get('newvotes', 'ServersController@getVotesUnclaimed')->name('.newVotes');
                Route::get('url','ServersController@getMyUrl')->name('.url');
            });

    });
