<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Server;
use Illuminate\Http\Request;

class ServersController extends Controller
{
    public function index(){
        return view("adminpanel.servers.list", ['servers'=>Server::all()]);
    }
}
