<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Player;
use App\Server;
use App\VoteSite;
use Faker\Generator as Faker;

$factory->define(\App\Vote::class, function (Faker $faker) {
    return [
        'for_server_id' => $faker->randomElement(Server::all())->id,
        'by_player_id'  => $faker->randomElement(Player::all())->id,
        'on_site_id'    => $faker->randomElement(VoteSite::all())->id,
        'claimed'       => $faker->randomElement([true,false]),
        'expires_at'    => $faker->dateTimeBetween('-48 hours','24 hours'),
    ];
});
