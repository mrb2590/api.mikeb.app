<?php

use App\File;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create my account
        $email = 'mrb2590@gmail.com';

        $user = User::create([
            'first_name' => 'Mike',
            'last_name' => 'Buonomo',
            'email' => $email,
            'slug' => str_slug(explode('@', $email)[0], '-'),
            'password' => bcrypt('apples'),
            'remember_token' => str_random(10),
            'api_token' => str_random(60),
        ]);

        $user->assignRole('super_user');

        // Create 50 random users
        factory(User::class, 50)->create()->each(function ($user) {
            $faker = Faker::create();

            $user->assignRole($faker->randomElement([
                'admin', 'member', 'viewer'
            ]));
        });

        // Create 50 random files
        factory(File::class, 50)->create();

    }
}
