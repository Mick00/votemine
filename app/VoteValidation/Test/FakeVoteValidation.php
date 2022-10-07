<?php


namespace App\VoteValidation\Test;


use App\VoteSite;
use App\VoteValidation\PlayerVoteValidator;

class FakeVoteValidation extends PlayerVoteValidator
{

    public $url;
    public $path;

    public function __construct(VoteSite $site)
    {
        parent::__construct($site);
        $this->url = "http://hello.com/api/v1/";
        $this->path = "server/".$this->SERVER_ID_PLACEHOLDER."/player/".$this->PLAYERNAME_PLACEHOLDER;
    }

    protected function getApiUrlWithPlaceholders(): string
    {
        return $this->url . $this->path;
    }

    protected function hasVoted($response): bool
    {
        return true;
    }

    protected function fetch($url)
    {
        return "oudnsanbdwan";
    }
}
