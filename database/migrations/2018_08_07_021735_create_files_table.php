<?php

use App\File;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

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
            $table->string('original_filename');
            $table->string('basename');
            $table->string('disk', 7);
            $table->string('path')->unique();
            $table->string('filename');
            $table->string('extension', 6);
            $table->string('mime_type');
            $table->bigInteger('size')->unsigned();
            $table->integer('directory')->unsigned()->nullable();
            $table->integer('owned_by')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('directory')->references('id')->on('directories')->onDelete('cascade');
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
        // Delete all files in the table
        File::chunk(500, function($files) {
            foreach ($files as $file) {
                Storage::disk($file->disk)->delete($file->path);
            }
        });

        Schema::dropIfExists('files');
    }
}