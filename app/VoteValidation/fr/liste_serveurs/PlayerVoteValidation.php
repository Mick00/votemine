<?php


namespace App\VoteValidation\fr\liste_serveurs;


use App\VoteValidation\PlayerVoteValidator;

class PlayerVoteValidation extends PlayerVoteValidator
{

    protected function getApiUrlWithPlaceholders(): string
    {
        return "https://www.liste-serveurs.fr/api/checkVote/".$this->SERVER_ID_PLACEHOLDER."/".$this->PLAYER_IP_PLACEHOLDER;
    }

    /**
     * @inheritDoc
     */
    protected function hasVoted($response): bool
    {
        return $response['success'];
    }
}
