<?php


namespace App\VoteValidation\com\serveurs_minecraft;


use App\VoteValidation\PlayerVoteValidator;
use DateTime;

class PlayerVoteValidation extends PlayerVoteValidator
{

    protected function getApiUrlWithPlaceholders(): string
    {
        return 'https://serveurs-minecraft.com/api.php?Classement=' . $this->SERVER_ID_PLACEHOLDER .'&ip=' . $this->PLAYER_IP_PLACEHOLDER;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    protected function hasVoted($response): bool
    {
        if ($response['lastVote'] === false){
            return false;
        }
        $currentDate = new DateTime($response['reqDate']['date'], new \DateTimeZone($response['reqDate']['timezone']));
        $voteDate = new DateTime($response['lastVote']['date'], new \DateTimeZone($response['lastVote']['timezone']));
        $interval = $currentDate->diff($voteDate);
        return $interval->y==0 && $interval->m==0 && $interval->d<1;
    }

}
