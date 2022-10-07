<?php

use App\Player;
use App\Vote;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VotedServerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $server = factory(\App\Server::class)->create(['name'=>'ManyVotes']);
        $site = factory(\App\VoteSite::class)->create();
        $site->addRegisteredServer($server,101);
        $players = factory(\App\Player::class,50)->create();
        $players->each(function(Player $player) use ($server, $site){
            factory(Vote::class, rand(1,300))->create([
                'on_site_id'=> $site->id,
                'for_server_id' => $server->id,
                'by_player_id' => $player->id,
            ]);
        });
    }
}
