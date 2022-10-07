<?php


namespace App\VoteValidation;


use App\Exceptions\FetchFailException;
use App\Server;
use App\VoteSite;
use Illuminate\Support\Str;

abstract class PlayerVoteValidator
{
    protected $SERVER_ID_PLACEHOLDER    = "{id}";
    protected $PLAYERNAME_PLACEHOLDER   = "{playername}";
    protected $PLAYER_IPV4_PLACEHOLDER  = "{ipv4}";
    protected $PLAYER_IPV6_PLACEHOLDER  = "{ipv6}";
    protected $PLAYER_IP_PLACEHOLDER    = "{ip}";
    protected $site;

    public function __construct(VoteSite $site)
    {
        $this->site = $site;
    }

    /**
     * @param Server $server
     * @param string $playername
     * @param string $ipv4
     * @param string $ipv6
     * @return bool
     */
    public function validate(Server $server, $playername, $ipv4 = null, $ipv6 = null): bool{
        try {
            $url = $this->getApiUrl($this->site->getServerId($server),$playername,$ipv4,$ipv6);
            $response = $this->fetch($url);
            return $this->hasVoted($response);
        } catch (\Exception $exception){
            return false;
        }
    }

    private function getApiUrl($serverIdOnSite, $playername=null, $ipv4=null, $ipv6=null):string{
        return Str::of($this->getApiUrlWithPlaceholders())
            ->replace($this->PLAYERNAME_PLACEHOLDER, $playername)
            ->replace($this->SERVER_ID_PLACEHOLDER, $serverIdOnSite)
            ->replace($this->PLAYER_IP_PLACEHOLDER, $ipv4)
            ->replace($this->PLAYER_IPV4_PLACEHOLDER, $ipv4)
            ->replace($this->PLAYER_IPV6_PLACEHOLDER, $ipv6);
    }

    protected abstract function getApiUrlWithPlaceholders(): string;

    protected function fetch($url){
        $response = file_get_contents($url);
        if ($response === false){
            throw new FetchFailException($url);
        }
        return $this->parseResponse($response);
    }

    private function parseResponse($response){
        $jsonResponse = json_decode($response, true);
        if (json_last_error() == JSON_ERROR_NONE){
            return $jsonResponse;
        }
        return $response;
    }

    /**
     * @param string|array $response
     * @return bool
     */
    protected abstract function hasVoted($response): bool;

}
