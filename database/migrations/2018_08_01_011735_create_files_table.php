<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uploaded_by')->unsigned();
            $table->string('original_filename');
            $table->string('bucket', 7);
            $table->string('path')->unique();
            $table->string('filename');
            $table->string('extension', 6);
            $table->string('mime_type');
            $table->bigInteger('size')->unsigned();
            $table->string('url')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
