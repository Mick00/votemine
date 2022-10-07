<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\VoteSite;
use Illuminate\Http\Request;

class VoteSitesController extends Controller
{
    public function index(){
        return view('adminpanel.votesites.index',['sites'=>VoteSite::all()]);
    }
}
