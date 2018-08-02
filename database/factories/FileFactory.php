<?php

use App\File;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Http\File as IlluminateFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(File::class, function (Faker $faker) {
    $testStoragePath  = storage_path('app/testing/sample_files');
    $sampleFiles = scandir($testStoragePath);

    unset($sampleFiles[0]);
    unset($sampleFiles[1]);

    $absPath = $testStoragePath.'/'.$faker->randomElement($sampleFiles);

    $disk = $faker->randomElement(['public', 'private']);

    $path = Storage::disk($disk)->putFile('files', new IlluminateFile($absPath));

    $originalPathinfo = pathinfo($absPath);
    $originalFilename = $originalPathinfo['filename'];

    $newAbsPath = storage_path('app/'.$disk).'/'.$path;

    $newFile = new UploadedFile($newAbsPath, $originalPathinfo['basename']);

    $pathInfo = pathinfo($path);

    return [
        'uploaded_by' => User::inRandomOrder()->first()->id,
        'original_filename' => $originalFilename,
        'basename' => $pathInfo['basename'],
        'disk' => $disk,
        'path' => $path,
        'filename' => $pathInfo['filename'],
        'extension' => $pathInfo['extension'],
        'mime_type' => $newFile->getMimeType(),
        'size' => Storage::disk($disk)->size($path),
        'url' => Storage::disk($disk)->url($path),
    ];
});
