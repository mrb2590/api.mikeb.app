<?php

use App\Directory;
use App\File;
use App\User;
use Faker\Generator as Faker;

$factory->define(Directory::class, function (Faker $faker) {
    // Get a random user who will own this file
    $user = User::inRandomOrder()->first();

    return [
        'name' => $faker->word,
        'disk' => File::$defaultDisk,
        'parent' => null,
        'owned_by' => $user->id,
        'created_by' => $user->id,
    ];
});
