<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Pages\PagesController@home');

Auth::routes();

Route::prefix('{locale}')->group(function (){
    Route::prefix('server/{server_name}')
        ->namespace('Server')
        ->middleware(['validate_server_name','locale'])
        ->name('server')
        ->group(function (){
            Route::get('/','ServersController@index');
            Route::get('statistic','LeaderboardController@index')->name('.statistic');
            Route::post('/validate', 'ServersController@validateVote')->name('.validate');
            Route::middleware(['auth','server_access'])
                ->group(function (){
                    Route::get('/edit','EditController@index')->name('.edit');
                    Route::patch('/edit','EditController@update')->name('.update');
                    Route::prefix('token')
                        ->name(".token")
                        ->group(function(){
                            Route::get('/', 'TokenController@index')->name('');
                            Route::post('/', 'TokenController@generateToken')->name('.create');
                            Route::delete('/','TokenController@deleteToken')->name('.delete');
                        });
                    Route::prefix('site')
                        ->name('.site')
                        ->group( function (){
                            Route::get('/','VotingSiteController@index')->name('');
                            Route::post('/','VotingSiteController@addSite')->name('.add');
                            Route::delete('/','VotingSiteController@deleteSite')->name('.delete');
                            Route::patch('/','VotingSiteController@updateSite')->name('.update');
                        });
                    Route::prefix('votifier')
                        ->name('.votifier')
                        ->group(function (){
                            Route::get('/', "VotifierController@index")->name('');
                            Route::patch('/',"VotifierController@update")->name('.update');
                            Route::get('test',"VotifierController@testVotifierSubmission")->name('.test');
                        });
                });
        });
});

Route::prefix('/home')
    ->namespace('Home')
    ->name('home')
    ->middleware(['auth','locale'])
    ->group(function(){
        Route::get('/', 'HomeController@index');
        Route::get('/myprofile', 'HomeController@myProfile')->name('.profile');
        Route::get('/addaserver', 'HomeController@addServerPage')->name('.addserver');
        Route::post('/addaserver', 'HomeController@addServerPost')->name('.create.server');
    });

Route::prefix('/admin')
    ->namespace('AdminPanel')
    ->name('adminpanel')
    ->middleware(['auth', 'locale'])
    ->group(function(){
        Route::get('/','AdminController@index');
        Route::get('/servers','ServersController@index')->name('.servers');
        Route::get('/users', 'UsersController@index')->name('.users');
        Route::get('/sites', 'VoteSitesController@index')->name('.votesites');
    });

