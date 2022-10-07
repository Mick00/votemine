<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\VoteValidation\PlayerVoteValidator;
use Faker\Generator as Faker;

$factory->define(\App\VoteSite::class, function (Faker $faker) {
    return[
        'url'=>'https://listevotemc.com',
        'name' => 'Liste de vote Mc',
        'logo_public_path' => 'logos/no-logo.png',
        'server_url' => 'https://listevotemc.com/serveur/{id}',
        'vote_lifespan' => 24,
        'validator' => \App\VoteValidation\org\serveursminecraft\PlayerVoteValidation::class
    ];
});
