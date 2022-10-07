<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\VotifierSettings::class, function (Faker $faker) {
    return [
        'ip' => '127.0.0.1',
        'port' => 8192,
        'token' => '4vi1j7n6p2353kt81vp2sudqia'
    ];
});
