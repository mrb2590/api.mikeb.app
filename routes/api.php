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

    /* Users */

    // Fetch current user
    Route::get('/user', 'UserController@fetch')->name('user.fetch');

    // Fetch users
    Route::get('/users/{user?}', 'UserController@fetch')
        ->where('user', '[0-9]+')->name('users.fetch');

    // Store user
    Route::post('/users', 'UserController@store')->name('users.store');

    // Update user
    Route::patch('/users/{user}', 'UserController@update')
        ->where('user', '[0-9]+')->name('users.update');

    // Trash user
    Route::delete('/users/{user}', 'UserController@trash')
        ->where('user', '[0-9]+')->name('users.trash');

    // Delete user
    Route::delete('/users/{trashedUser}/force', 'UserController@delete')
        ->where('trashedUser', '[0-9]+')->name('users.delete');

    // Restore user
    Route::post('/users/{trashedUser}/restore', 'UserController@restore')
        ->where('trashedUser', '[0-9]+')->name('users.restore');

    /* Roles */

    // Fetch roles
    Route::get('/roles/{role?}', 'RoleController@fetch')
        ->where('role', '[0-9]+')->name('roles.fetch');

    // Fetch roles with permissions
    Route::get('/roles/permissions', 'RoleController@fetchPermissions')
        ->where('role', '[0-9]+')->name('roles.permissions.fetch');

    /* Files */

    // Fetch files
    Route::get('/files/{file?}', 'FileController@fetch')
        ->where('file', '[0-9]+')->name('files.fetch');

    // Store file
    Route::post('/files', 'FileController@store')->name('files.store');

    // Update file
    Route::patch('/files/{file}', 'FileController@update')
        ->where('file', '[0-9]+')->name('files.update');

    // Trash file
    Route::delete('/files/{file}', 'FileController@trash')
        ->where('file', '[0-9]+')->name('files.trash');

    // Delete file
    Route::delete('/files/{trashedFile}/force', 'FileController@delete')
        ->where('trashedFile', '[0-9]+')->name('files.delete');

    // Restore file
    Route::post('/files/{trashedFile}/restore', 'FileController@restore')
        ->where('trashedFile', '[0-9]+')->name('files.restore');

    /* Files */

    // Fetch directories
    Route::get('/directories/{directory?}', 'DirectoryController@fetch')
        ->where('directory', '[0-9]+')->name('directories.fetch');

    // Store directory
    Route::post('/directories', 'DirectoryController@store')->name('directories.store');

    // Update directory
    Route::patch('/directories/{directory}', 'DirectoryController@update')
        ->where('directory', '[0-9]+')->name('directories.update');

    // Trash directory
    Route::delete('/directories/{directory}', 'DirectoryController@trash')
        ->where('directory', '[0-9]+')->name('directories.trash');

    // Delete directory
    Route::delete('/directories/{trashedFile}/force', 'DirectoryController@delete')
        ->where('trashedFile', '[0-9]+')->name('directories.delete');

    // Restore directory
    Route::post('/directories/{trashedFile}/restore', 'DirectoryController@restore')
        ->where('trashedFile', '[0-9]+')->name('directories.restore');
});
