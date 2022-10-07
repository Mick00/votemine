<?php

namespace App\Http\Controllers\Server;

use App\Http\Controllers\Controller;
use App\Player;
use App\Server;
use App\VoteValidation\VoteHandler;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;

class ServersController extends Controller
{
    public function index(){
        $server = Server::current();
        SEOTools::setTitle($server->name);
        SEOTools::setDescription($server->description);
        $images = [];
        if ($server->banner){
            $images[] = $server->banner->getOriginalFilePath();
        }
        if ($server->logo){
            $images[] = $server->logo->getOriginalFilePath();
        }
        SEOTools::addImages($images);
        SEOTools::opengraph()->setUrl(route('server',['name'=>$server->name]));
        SEOTools::opengraph()->addProperty('type', 'articles');
        SEOTools::opengraph()->setSiteName(env('APP_NAME','Votemine'));
        SEOTools::jsonLd()->addValues([
            '@context'=>'https://schema.org',
            '@type'=> 'GameServer',
            'game' => 'Minecraft',
            'serverStatus'=>$server->online?"Online":"OfflineTemporarly",
            'playersOnline'=>$server->playerCount,
            'name'=>$server->name,
            'mainEntityOfPage'=>$server->website_url,
            ]
        );
        return view('servers.index', ['server'=>$server]);
    }

    public function validateVote(Request $request){
        $server = Server::current();
        $data = $request->validate([
            'playername' => 'required'
        ]);
        $player = new Player([
            'name' => $data['playername'],
        ]);
        if(filter_var($request->ip(), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            $player->ipv4 = $request->ip();
        } else if(filter_var($request->ip(), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)){
            $player->ipv6 = $request->ip();
        }
        $votes = VoteHandler::submitVotes($player, $server);
        return redirect()->route('server')
            ->withSuccess(trans(':votesCount vote(s) validated',['votesCount'=>count($votes)]))
            ->cookie('player_name', $player->name, 10080);
    }
}
