<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Player;
use Faker\Generator as Faker;

$factory->define(Player::class, function (Faker $faker) {
    $username = substr($faker->userName(),0,16);
    $username = str_replace(".",'',$username);
    $dash = array('-', '_');
    while (!ctype_alnum(str_replace($dash, '', $username))){
        echo $username . ", ";
        $username = substr($faker->userName,0,16);
    }
    return [
        'name' => $username,
        'uuid' => $faker->uuid,
        'ipv4' => $faker->ipv4,
        'ipv6' => $faker->ipv6,
    ];
});
