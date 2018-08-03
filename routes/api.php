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
	Route::get('/user', 'UsersController@fetch')->name('user.fetch');

	// Fetch all users
	Route::get('/users', 'UsersController@fetchAll')->name('users.fetch');

	// Fetch user files by disk or all
	Route::get('/user/files/{disk?}', 'UsersController@fetchUserFiles')->name('user.files.fetch');

	/* Files */

	// Fetch all or a single file
	Route::get('/files/{file?}', 'FilesController@fetch')
		->where('file', '[0-9]+')->name('files.fetch');

	// Fetch files by disk
	Route::get('/files/{disk}', 'FilesController@fetchDisk')
		->where('disk', 'public|private')->name('files.disk.fetch');

	// Upload files
	Route::post('/files', 'FilesController@store')->name('files.store');

	// Update file
	Route::match(['get', 'post'], '/files/{file}', 'FilesController@delete')
		->where('file', '[0-9]+')->name('files.update');

	// Remove file
	Route::delete('/files/{file}', 'FilesController@delete')
		->where('file', '[0-9]+')->name('files.delete');

	// Restore file
	Route::post('/files/{id}/restore', 'FilesController@restore')
		->where('file', '[0-9]+')->name('files.restore');

});
