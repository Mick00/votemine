<?php


namespace App\VoteValidation;


use App\Jobs\NotifyServerJob;
use App\Player;
use App\Server;
use App\Vote;
use App\VoteSite;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class VoteHandler
{

    public static function submitVotes(Player $player, Server $server):array {
        $validator = new ServerVoteValidator($server);
        $playersVotes = $validator->getVotesOf($player);
        $votes = [];
        if (!empty($playersVotes)){
            $player = Player::firstOrCreate($player->getAttributes());
            $ip = $player->ipv4 ?? $player->ipv6;
            foreach ($playersVotes as $siteVote){
                if(!self::existsValidVote($server, $siteVote, $ip, $player->name)){
                    $votes[] = self::saveVote($siteVote, $server, $player);
                    NotifyServerJob::dispatch($siteVote, $server, $player);
                }
            }
        }
        return $votes;
    }

    public static function saveVote(VoteSite $site, Server $server, Player $player):Vote{
        $vote = new Vote([
            'expires_at' => Carbon::now()->addHours($site->vote_lifespan)
        ]);
        $vote->onSite()->associate($site);
        $vote->byPlayer()->associate($player);
        $vote->forServer()->associate($server);
        $vote->save();
        return $vote;
    }

    public static function notifyServer(VoteSite $site, Server $server, Player $player){
        $settings = $server->votifierSettings;
        $vote = new \Imaginarycode\Votifier2\Vote($site->name." via Votemine.com", $player->name, $player->ipv4??$player->ipv6, NULL);
        $service = new \Imaginarycode\Votifier2\Server($settings->ip, $settings->port, $settings->token);
        $service->sendVote($vote);
    }

    public static function fetchUnclaimedVotes(Server $server){
        return Vote::unclaimed()->whereServer($server)->with('byPlayer')->get();
    }

    public static function fetchValidVotes(Player $player, Server $server):Collection{
        return Vote::valid()->wherePlayer($player)->whereServer($server)->get();
    }

    public static function existsValidVote(Server $server, VoteSite $site, $ip, $playername = null){
        return self::getVote($server, $site, $ip, $playername) != null;
    }

    public static function lastVote(Server $server, VoteSite $site, $ip, $playername = null){
        return self::getVote($server, $site, $ip, $playername);
    }

    public static function getVote(Server $server, VoteSite $site, $ip, $playername = null){
        $keyCache = 'vote.'.$server->id.".".$site->id.".".$ip;
        $vote = Cache::get($keyCache);
        if ($vote == null){
            $vote = Vote::valid()
                ->whereServer($server)
                ->whereSite($site)
                ->whereIn('by_player_id',function($query) use ($ip, $playername){
                    $query->select('players.id')
                        ->from('players')
                        ->where(function ($query) use ($ip, $playername){
                            $query->where('players.ipv4',$ip)
                                ->orWhere('players.ipv6',$ip);
                            if ($playername != null){
                                $query->orWhere('players.name', $playername);
                            }
                        });
                })->first();
            if ($vote != null){
                $timeLeft = $vote->timeLeft();
                Cache::put($keyCache, $vote, $timeLeft->h*3600+($timeLeft->i+1)*60);
            }
        }
        return $vote;
}

}
