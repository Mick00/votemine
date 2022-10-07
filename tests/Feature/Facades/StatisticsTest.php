<?php

namespace Tests\Feature\Facades;

use App\Player;
use App\Server;
use App\Vote;
use Carbon\Carbon;
use Tests\SeededTest;
use \Statistics;
class StatisticsTest extends SeededTest
{

    public function testGetLeadersTotalReturned(){
        $nVotes = 10;
        $player = factory(Player::class)->create();
        $this->createVote($player,$nVotes);
        $this->assertEquals($nVotes, Statistics::getLeaders($this->server)->first()->total);
    }

    public function testGetLeadersTotalReturned2Accounts(){
        $player1 = factory(Player::class)->create(['name'=>'buster']);
        $player2 = factory(Player::class)->create(['name'=>'buster']);
        $this->createVote($player1);
        $this->createVote($player2);
        $this->assertEquals(2, Statistics::getLeaders($this->server)->first()->total);
    }

    public function testOutdatedVoteOutdatedNotInTotal(){
        $this->createVoteDated(
            factory(Player::class)->create(),
            Carbon::now()->floorMonth()->subMinute());
        $this->assertEmpty(Statistics::getLeaders($this->server));
    }

    private function createVoteDated(Player $player,Carbon $date){
        return factory(Vote::class)->create([
            'for_server_id'=> $this->server->id,
            'by_player_id'=> $player->id,
            'created_at'=> $date->toDateString()
        ]);
    }

    public function testUpdatedVoteNotInTotal(){
        $this->createVoteDated(factory(Player::class)->create(),
            Carbon::now()->ceilMonth()->addMinute());
        $this->assertEmpty(Statistics::getLeaders($this->server));
    }

    public function testGetLeadersPlayerVotesAreMerged()
    {
        $player = factory(Player::class)->create();
        $this->createVote($player, 2);
        $this->assertCount(1, Statistics::getLeaders($this->server));
    }

    private function createVote(Player $player, $n = 1){
        return factory(Vote::class,$n)->create([
            'for_server_id'=> $this->server->id,
            'by_player_id'=> $player->id,
        ]);
    }

    public function testGetLeadersPlayerNamesAreMerged(){
        $player1 = factory(Player::class)->create(['name'=>'buster']);
        $player2 = factory(Player::class)->create(['name'=>'buster']);
        $this->createVote($player1);
        $this->createVote($player2);
        $this->assertCount(1, Statistics::getLeaders($this->server));
    }

    public function testVotesToday(){
        $this->createVoteAtDate(Carbon::today());
        $this->assertEquals(1, Statistics::votesToday($this->server));
    }

    public function testVotesYesterday(){
        $this->createVoteAtDate(Carbon::yesterday());
        $this->assertEquals(0, Statistics::votesToday($this->server));
    }

    public function testVotesTomorrow(){
        $this->createVoteAtDate(Carbon::tomorrow());
        $this->assertEquals(0, Statistics::votesToday($this->server));
    }

    public function createVoteAtDate(Carbon $date){
        $this->createVoteDated(factory(Player::class)->create(), $date);
    }

}
