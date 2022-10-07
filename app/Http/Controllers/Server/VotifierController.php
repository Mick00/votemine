<?php

namespace App\Http\Controllers\Server;

use App\Http\Controllers\Controller;
use App\Server;
use App\VotifierSettings;
use Illuminate\Http\Request;
use Imaginarycode\Votifier2\Vote;

class VotifierController extends Controller
{
    public function index(){
        return view('servers.votifier',['server'=>Server::current()]);
    }

    public function update(Request $request){
        $server = Server::current();
        if ($server->isVotifierEnabled()){
            $server->votifierSettings->update($request->input());
        } else {
            $votifierSettings = VotifierSettings::create($request->input());
            $server->votifierSettings()->associate($votifierSettings);
            $server->save();
        }
        return redirect()->route('server.votifier')->withSuccess(__('Your votifier settings have been save. You should test them.'));
    }

    public function testVotifierSubmission(Request $request){
        $server = Server::current();
        $settings = $server->votifierSettings;
        if ($server->isVotifierEnabled()){
            try {
                $vote = new Vote(env('APP_NAME','Votemine'), $request->input('username'), "127.0.0.1", NULL);
                $service = new \Imaginarycode\Votifier2\Server($settings->ip, $settings->port, $settings->token);
                $service->sendVote($vote);
            } catch (\ErrorException $e) {
                echo $e;
                return "Server unreachable, verify that the port is open";
            } catch (\Exception $e){
                return "Verify Votifier token";
            }
            return "sent";
        } else {
            return "You have to enable votifier first";
        }
    }
}
