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

    // Fetch current user
    Route::get('/user', 'UsersController@fetch')->name('user.fetch');

    // Fetch users
    Route::get('/users/{user?}', 'UsersController@fetch')
        ->where('user', '[0-9]+')->name('users.fetch');

    /* Files */

    // Fetch files
    Route::get('/files/{file?}', 'FilesController@fetch')
        ->where('file', '[0-9]+')->name('files.fetch');

    // Upload files
    Route::post('/files', 'FilesController@store')->name('files.store');

    // Trash file
    Route::delete('/files/{file}', 'FilesController@trash')
        ->where('file', '[0-9]+')->name('files.trash');

    // Delete file
    Route::delete('/files/{trashedFile}/force', 'FilesController@delete')
        ->where('trashedFile', '[0-9]+')->name('files.delete');

    // Restore file
    Route::post('/files/{trashedFile}/restore', 'FilesController@restore')
        ->where('trashedFile', '[0-9]+')->name('files.restore');

    // /* Roles */

    // Fetch roles
    Route::get('/roles/{role?}', 'RolesController@fetch')
        ->where('role', '[0-9]+')->name('roles.fetch');

    // Fetch roles with permissions
    Route::get('/roles/permissions', 'RolesController@fetchPermissions')
        ->where('role', '[0-9]+')->name('roles.permissions.fetch');
});
