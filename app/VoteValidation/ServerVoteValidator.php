<?php


namespace App\VoteValidation;


use App\Player;
use App\Server;

class ServerVoteValidator
{
    protected $server;
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function getVotesOf(Player $player): array{
        $votedOn = [];
        foreach ($this->server->voteSites as $votingSite){
            if($votingSite->validator->validate($this->server, $player->name, $player->ipv4,$player->ipv6)){
                $votedOn[] = $votingSite;
            }
        }
        return $votedOn;
    }
}
