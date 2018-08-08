<?php

use App\File;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('slug');
            $table->string('password');
            $table->char('api_token', 60);
            $table->integer('status_id')->unsigned();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Delete all user storage directories from all disks
        User::chunk(500, function($users) {
            foreach ($users as $user) {
                foreach (File::$disks as $disk) {
                    Storage::disk($disk)->deleteDirectory($user->storage_dir);
                }
            }
        });

        Schema::dropIfExists('users');
    }
}
