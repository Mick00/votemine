<?php


namespace App\VoteValidation\org\serveurs_minecraft;


use App\VoteValidation\PlayerVoteValidator;

class PlayerVoteValidation extends PlayerVoteValidator
{

    protected function getApiUrlWithPlaceholders(): string
    {
        return "https://www.serveurs-minecraft.org/api/is_valid_vote.php?id=".$this->SERVER_ID_PLACEHOLDER."&ip=".$this->PLAYER_IP_PLACEHOLDER."&duration=".($this->site->vote_lifespan*60)."&format=json";
    }

    protected function hasVoted($response): bool
    {
        return isset($response['votes'])?$response['votes']:false;
    }
}
