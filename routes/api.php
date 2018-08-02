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

	/* User */

	// Fetch user
	Route::get('/user', 'UsersController@fetch')->name('fetch-user');

	// Fetch user files by disk or all
	Route::get('/user/files/{disk?}', 'UsersController@fetchUserFiles')->name('fetch-user-files');

	/* Files */

	// Fetch all or a single file
	Route::get('/files/{file?}', 'FilesController@fetch')
		->where('file', '[0-9]+')->name('fetch-files');

	// Fetch files by disk
	Route::get('/files/{disk}', 'FilesController@fetchDisk')
		->where('disk', 'public|private')->name('fetch-files-disk');

	// Upload files
	Route::post('/files', 'FilesController@store')->name('upload-files');

});
