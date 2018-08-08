<?php

use App\Folder;
use App\File;
use App\User;
use Faker\Generator as Faker;

$factory->define(Folder::class, function (Faker $faker) {
    // Get a random user who will own this file
    $user = User::inRandomOrder()->first();

    return [
        'name' => $faker->word,
        'disk' => File::$defaultDisk,
        'parent_id' => null,
        'owned_by_id' => $user->id,
        'created_by_id' => $user->id,
    ];
});
