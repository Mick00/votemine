<?php

namespace Tests\Feature;

use App\Player;
use App\Server;
use App\Vote;
use App\VoteSite;
use App\VoteValidation\VoteHandler;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\SeededTest;
use Tests\TestCase;

class VoteTest extends SeededTest
{
    use RefreshDatabase;

    public function setUp(): void
    {
      parent::setUp();
      factory(Vote::class,100)->create();
    }

    public function testScopeUnclaimed()
    {
        $unclaimedVoteCountByScope = Vote::unclaimed()->count();
        $unclaimedCountQueried = DB::table('votes')->where('claimed','=', false)->get()->count();
        $this->assertEquals($unclaimedCountQueried, $unclaimedVoteCountByScope);
    }

    public function testValidScope(){
        $scopedVotes = Vote::valid()->get();
        $filteredVotes = Vote::all()->filter(function (Vote $value){
            return $value->expires_at > Carbon::now();
        })->all();
        $this->assertEmpty($scopedVotes->diff($filteredVotes));
    }

    public function testWhereServerScope(){
        $voteCount = 10;
        $server = factory(Server::class)->create();
        factory(Vote::class,$voteCount)->create([
            'for_server_id' => $server->id,
        ]);
        $votes = Vote::whereServer($server)->get();
        $this->assertEquals($voteCount, count($votes));
    }

    public function testWherePlayerScope(){
        $voteCount = 10;
        $player = factory(Player::class)->create();
        factory(Vote::class,$voteCount)->create([
            'by_player_id' => $player->id,
        ]);
        $votes = Vote::wherePlayer($player)->get();
        $this->assertEquals($voteCount, count($votes));
    }

    public function testWhereSiteScope(){
        $voteCount = 10;
        $site = factory(VoteSite::class)->create();
        factory(Vote::class,$voteCount)->create([
            'on_site_id' => $site->id,
        ]);
        $votes = Vote::whereSite($site)->get();
        $this->assertEquals($voteCount, count($votes));
    }

    public function testClaim(){
        $vote = factory(Vote::class)->make(['claimed'=>false]);
        $vote->claim();
        $this->assertTrue($vote->claimed);
    }

    public function testClaimException(){
        $this->expectException(\Exception::class);
        $vote = factory(Vote::class)->make();
        $vote->claim();
        $vote->claim();
    }
}
