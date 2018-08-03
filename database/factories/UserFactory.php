<?php

use App\Status;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function(Faker $faker) {
	$email = $faker->unique()->safeEmail;
    $statuses = Status::where('type', 'user')->get();

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'slug' => str_slug(explode('@', $email)[0], '-'),
        'password' => bcrypt('testing123'),
        'remember_token' => str_random(10),
    	'api_token' => str_random(60),
        'status_id' => $statuses[$faker->biasedNumberBetween(
            $statuses->count() - 1, 1, 'sqrt'
        )]->id,
    ];
});
