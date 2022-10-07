<?php


namespace App\VoteValidation\com\liste_minecraft_serveurs;


use App\VoteValidation\PlayerVoteValidator;

class PlayerVoteValidation extends PlayerVoteValidator
{

    protected function getApiUrlWithPlaceholders(): string
    {
        return "https://www.liste-minecraft-serveurs.com/Api/Worker/id_server/".$this->SERVER_ID_PLACEHOLDER."/ip/".$this->PLAYER_IP_PLACEHOLDER;
    }

    /**
     * @inheritDoc
     */
    protected function hasVoted($response): bool
    {
        return isset($response['result'])? $response['result'] == 202: false;
    }
}
