<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('disk', 7);
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('owned_by_id')->unsigned();
            $table->integer('created_by_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('folders')->onDelete('cascade');
            $table->foreign('owned_by_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('folder_id')->unsigned()->nullable()->after('status_id');

            $table->foreign('folder_id')->references('id')->on('folders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_folder_id_foreign');

            $table->dropColumn('folder_id');
        });

        Schema::dropIfExists('folders');
    }
}
