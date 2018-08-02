<?php

use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $factory->define(User::class, function (Faker $faker) {
            $email = $faker->unique()->safeEmail;

            return [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'slug' => str_slug(explode('@', $email)[0], '-'),
                'password' => bcrypt('testing123'),
                'remember_token' => str_random(10),
                'api_token' => str_random(60),
            ];
        });
    }
}
