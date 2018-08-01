<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Routes require authentication
Route::middleware(['auth:api'])->prefix('v1')->group(function() {

	Route::get('/user', 'UsersController@fetch')->name('fetch-user');

	Route::get('/files/{file?}', 'FilesController@fetch')
		->where('file', '[0-9]+')->name('fetch-files');

	Route::get('/files/{bucket}', 'FilesController@fetchBucket')
		->where('bucket', 'public|private')->name('fetch-bucket');

	Route::post('/files', 'FilesController@store')->name('store-files');

});
