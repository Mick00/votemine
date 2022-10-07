<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use App\Model;
use App\Upload;
use App\User;
use App\Version;
use Faker\Generator as Faker;

$factory->define(App\Server::class, function (Faker $faker) {
    $owner = null;
    if(rand(0,1)){
        $owner = factory(App\User::class)->create();
    } else {
        $owner = User::all()->first();
    }

    return [
        'name' => $faker->unique()->company,
        'ip' => "127.0.0.1",
        'description' => join('</br>', $faker->paragraphs(2)),
        'website_url' => $faker->randomElement(['https://station47.net', null]),
        'owned_by_id' => $owner->id,
        'version_id' => $faker->randomElement(Version::all())->id,
        'banner_id' => Image::newBanner(\Illuminate\Http\UploadedFile::fake()->image('allo.jpg'), $owner),
        'logo_id'=>Image::newLogo(\Illuminate\Http\UploadedFile::fake()->image('allo1.jpg'), $owner),
        //'votifier_settings_id' => factory(\App\VotifierSettings::class)->create()
    ];
});
