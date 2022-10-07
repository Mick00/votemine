<?php

namespace Tests\Feature;

use App\Server;
use App\VoteSite;
use App\VoteValidation\PlayerVoteValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SeededTest;
use Tests\TestCase;

class SiteVoteTest extends SeededTest
{
    use RefreshDatabase;

    public function testAddServer()
    {
        $idOnSite = 10;
        $this->site->addRegisteredServer($this->server,$idOnSite);
        $this->assertServerIsRegisteredForSite($idOnSite);
        $this->assertEquals($idOnSite, $this->site->getServerId($this->server));
        $newIdOnSite = 11;
        $this->site->defineServerId($this->server, $newIdOnSite);
        $this->assertServerIsRegisteredForSite($newIdOnSite);
        $this->assertEquals($newIdOnSite, $this->site->getServerId($this->server));
    }

    private function assertServerIsRegisteredForSite($idOnSite){
        $this->assertDatabaseHas("server_vote_site",[
            'vote_site_id' => $this->site->id,
            'server_id'    => $this->server->id,
            'server_id_on_site' => $idOnSite,
        ]);
    }

    public function testSiteToServer(){
        $this->site->servers()->attach($this->server);
        $this->site->save();
        $fetchedSite = VoteSite::where('name', $this->site->name)->first();
        $this->assertNotNull($fetchedSite);
        $this->assertEquals(1, $fetchedSite->servers->count());
    }

    public function testValidatorCasting()
    {
        $site = VoteSite::where('name', $this->site->name)->first();
        $this->assertInstanceOf(PlayerVoteValidator::class,$site->validator);
    }

    public function testGetServerUrl(){
        $serverIdOnSite = 100;
        $this->site->addRegisteredServer($this->server, 100);
        $url = $this->site->getServerUrlOnSite($this->server);
        $this->assertNotEquals($url,$this->site->server_url);
    }
}
