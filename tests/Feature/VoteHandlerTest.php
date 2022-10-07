<?php

namespace Tests\Feature;

use App\Player;
use App\Server;
use App\Vote;
use App\VoteValidation\ServerVoteValidator;
use App\VoteValidation\VoteHandler;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class VoteHandlerTest extends ServerVoteValidatorTest
{
    use RefreshDatabase;
    protected $player;
    protected $newPlayer;

    public function setUp(): void
    {
        parent::setUp();
        $this->player = factory(Player::class)->create();
        $this->newPlayer = factory(Player::class)->make();
    }

    public function testSubmitVoteSaveToDB()
    {
        $this->submitVotes();
        $this->assertDatabaseHas('votes',$this->getVoteEntry($this->siteAlwaysValid));
        $this->assertDatabaseMissing('votes',$this->getVoteEntry($this->siteAlwaysInvalid));
    }

    private function submitVotes(){
        //The server is registered to fake validation: one always true, one always false
        return VoteHandler::submitVotes($this->player, $this->server);
    }

    private function getVoteEntry($site){
        return [
            'for_server_id' =>$this->server->id,
            'by_player_id'  =>$this->player->id,
            'on_site_id'    =>$site->id,
        ];
    }

    public function testArrayReturned(){
        $votes = $this->submitVotes();
        $this->assertEquals(1,sizeof($votes));
    }

    public function testNoPlayerDataDuplicate(){
        $this->submitVotesNewPlayer();
        $this->assertEquals(1,Player::where('name',$this->player->name)->count());
    }

    private function submitVotesNewPlayer(){
        return VoteHandler::submitVotes(new Player($this->player->getAttributes()), $this->server);
    }

    public function testResubmitVote(){
        $this->submitVotes();
        $this->assertTrue(empty($this->submitVotes()));
    }

    public function testFetchUnclaimedVotes(){
        $server = factory(Server::class)->create();
        $votes = factory(Vote::class,10)->create(['for_server_id'=>$server->id]);
        $fetched = VoteHandler::fetchUnclaimedVotes($server);
        $votes = $votes->filter(function (Vote $vote){ return !$vote->claimed; });
        $this->assertTrue($votes->diff($fetched)->isEmpty());
    }

    public function testValidVotes(){
        $newVotes = factory(Vote::class,30)->create();
       $server = factory(Server::class)->create();
       $player = factory(Player::class)->create();
       $votes = factory(Vote::class,10)->create([
           'for_server_id' => $server->id,
           'by_player_id'   => $player->id,
       ]);
       $noVotesFromOtherServer = $newVotes->intersect(VoteHandler::fetchValidVotes($player, $server))->isEmpty();
       $this->assertTrue($noVotesFromOtherServer);
    }

    public function testExistsValidVote(){
        $this->submitVotes();
        $this->assertTrue(VoteHandler::existsValidVote($this->server,$this->siteAlwaysValid, $this->player->ipv4));
        $this->assertTrue(VoteHandler::existsValidVote($this->server,$this->siteAlwaysValid, $this->player->ipv6));
        $this->assertFalse(VoteHandler::existsValidVote($this->server,$this->siteAlwaysInvalid, $this->player->ipv4));
        $this->assertFalse(VoteHandler::existsValidVote($this->server,$this->siteAlwaysInvalid, $this->player->ipv6));
    }

    public function testGetVoteSamePlayerDiffIp(){
        $votes = $this->submitVotes();
        $this->assertEquals(1, count($votes));
        $this->assertTrue(VoteHandler::existsValidVote($this->server,$this->siteAlwaysValid, "notanip", $this->player->name));
        $this->assertFalse(VoteHandler::existsValidVote($this->server,$this->siteAlwaysInvalid,"notanip", $this->player->name));
    }

    public function testGetVoteNotCached(){
        $vote = $this->submitVotes()[0];
        $keyCache = $this->getVoteCacheKey();
        Cache::shouldReceive('get')
            ->once()
            ->with($keyCache)
            ->andReturn(null);
        $vote->refresh();
        $timeLeft = $vote->timeLeft();
        $timeLeft = $timeLeft->h*3600+($timeLeft->i+1)*60;
        Cache::shouldReceive('put')
            ->once()
            ->withSomeOfArgs($keyCache, $timeLeft);
        $this->assertEquals($this->player->id, VoteHandler::getVote($this->server,$this->siteAlwaysValid,$this->player->ipv4)->byPlayer->id);
    }

    public function getVoteCacheKey(){
        return $keyCache = 'vote.'.$this->server->id.".".$this->siteAlwaysValid->id.".".$this->player->ipv4;
    }

    public function testGetVoteCached(){
        $keyCache = $this->getVoteCacheKey();
        $vote = new Vote(['expires_at'=> Carbon::now()->add('+ 10 minutes')]);
        Cache::shouldReceive('get')
            ->once()
            ->withSomeOfArgs($keyCache)
            ->andReturn($vote);
        $this->assertEquals($vote, VoteHandler::getVote($this->server,$this->siteAlwaysValid,$this->player->ipv4));
    }

}
