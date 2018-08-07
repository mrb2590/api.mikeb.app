<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('disk', 7);
            $table->integer('parent')->unsigned()->nullable();
            $table->integer('owned_by')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parent')->references('id')->on('directories')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('no action');
            $table->foreign('owned_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('directories');
    }
}
