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
            $table->string('display_filename');
            $table->string('basename');
            $table->string('disk', 7);
            $table->string('path')->unique();
            $table->string('filename');
            $table->string('extension', 6);
            $table->string('mime_type');
            $table->bigInteger('size')->unsigned();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('owned_by_id')->unsigned();
            $table->integer('created_by_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('folders')->onDelete('cascade');
            $table->foreign('owned_by_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['display_filename', 'parent_id']);
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
