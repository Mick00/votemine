<?php


namespace App\VoteValidation\org\liste_serveurs_minecraft;


use App\VoteValidation\PlayerVoteValidator;

class PlayerVoteValidation extends PlayerVoteValidator
{

    protected function getApiUrlWithPlaceholders(): string
    {
        return "https://api.liste-serveurs-minecraft.org/vote/vote_verification.php?server_id=".$this->SERVER_ID_PLACEHOLDER."&ip=".$this->PLAYER_IP_PLACEHOLDER."&duration=180";
    }

    /**
     * @inheritDoc
     */
    protected function hasVoted($response): bool
    {
        return $response == 1? true : false;
    }
}
