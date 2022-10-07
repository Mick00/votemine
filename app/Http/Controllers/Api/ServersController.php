<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Server;
use App\Vote;
use App\VoteValidation\VoteHandler;
use Flugg\Responder\Facades\Responder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Collection;

class ServersController extends Controller
{
    public function getVotesUnclaimed(Request $request){
        $peeking = $request->query('peek', false);
        $votes = VoteHandler::fetchUnclaimedVotes(Auth::user());
        if (!$peeking){
            $votes->each(function (Vote $vote){
                $vote->claim();
            });
        }
        return Responder::success($votes);
    }

    public function getMyUrl(){
        return Responder::success(['url' => url("server", ['name'=>Auth::user()->name])]);
    }
}
