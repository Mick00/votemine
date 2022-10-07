<?php


namespace App\VoteValidation\org\serveursminecraft;


use App\VoteValidation\PlayerVoteValidator;

class PlayerVoteValidation extends PlayerVoteValidator
{

    protected function getApiUrlWithPlaceholders(): string
    {
        return "https://www.serveursminecraft.org/sm_api/peutVoter.php?id=".$this->SERVER_ID_PLACEHOLDER."&ip=".$this->PLAYER_IP_PLACEHOLDER;
    }

    /**
     * @param string|array $canVote if the player can vote, the seconds remaining before the player can vote vote again otherwise
     * @return bool
     */
    protected function hasVoted($canVote): bool
    {
        return $canVote > 1? true: false;
    }
}
