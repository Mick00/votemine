<?php

namespace App\Http\Controllers\Server;

use App\Http\Controllers\Controller;
use App\Server;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(){
        return view('servers.leaderboard',['server'=>Server::current()]);
    }
}
