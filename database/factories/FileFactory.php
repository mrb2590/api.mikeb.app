<?php

use App\Folder;
use App\File;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Http\File as IlluminateFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(File::class, function(Faker $faker) {
    // Get a random user who will own this file
    $user = User::inRandomOrder()->first();

    // Get a random sample file
    $sampleStoragePath = storage_path('app/testing/sample_files');
    $sampleFiles = array_diff(scandir($sampleStoragePath), ['.', '..']);
    $sampleAbsPath = $sampleStoragePath.'/'.$faker->randomElement($sampleFiles);
    $samplePathinfo = pathinfo($sampleAbsPath);
    $sampleFilename = $samplePathinfo['filename'];

    // Copy the file to the disk
    $disk = File::$defaultDisk;
    $filePath = Storage::disk($disk)
        ->putFile($user->storage_dir, new IlluminateFile($sampleAbsPath));
    $fileAbsPath = storage_path('app/'.$disk).'/'.$filePath;
    $filePathInfo = pathinfo($filePath);

    return [
        'display_filename' => $sampleFilename,
        'basename' => $filePathInfo['basename'],
        'disk' => $disk,
        'path' => $filePath,
        'filename' => $filePathInfo['filename'],
        'extension' => $filePathInfo['extension'],
        'mime_type' => (new UploadedFile($fileAbsPath, $samplePathinfo['basename']))->getMimeType(),
        'size' => Storage::disk($disk)->size($filePath),
        'parent_id' => $faker->RandomElement([null, Folder::inRandomOrder()->first()->id]),
        'owned_by_id' => $user->id,
        'created_by_id' => $user->id,
    ];
});
