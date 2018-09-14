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

    // Login user
    Route::post('/user/login', 'Auth\LoginController@loginProxy')->name('user.login');

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

    /* Folders */

    // Fetch folders
    Route::get('/folders/{folder?}', 'FolderController@fetch')
        ->where('folder', '[0-9]+')->name('folders.fetch');

    // Fetch folder tree
    Route::get('/folders/{folder}/tree', 'FolderController@fetchTree')
        ->where('folder', '[0-9]+')->name('folders.tree.fetch');

    // Fetch files from folder
    Route::get('/folders/{folder}/files', 'FolderController@fetchFiles')
        ->where('folder', '[0-9]+')->name('folders.files.fetch');

    // Download folder as zip
    Route::get('/folders/{folder}/download', 'FolderController@download')
        ->where('folder', '[0-9]+')->name('folders.download');

    // Store folder
    Route::post('/folders', 'FolderController@store')->name('folders.store');

    // Update folder
    Route::patch('/folders/{folder}', 'FolderController@update')
        ->where('folder', '[0-9]+')->name('folders.update');

    // Move folder
    Route::patch('/folders/{folder}/move', 'FolderController@move')
        ->where('folder', '[0-9]+')->name('folders.move');

    // Change Owner of a folder
    Route::patch('/folders/{folder}/chown', 'FolderController@changeOwner')
        ->where('folder', '[0-9]+')->name('folders.chown');

    // Trash folder
    Route::delete('/folders/{folder}', 'FolderController@trash')
        ->where('folder', '[0-9]+')->name('folders.trash');

    // Delete folder
    Route::delete('/folders/{trashedFolder}/force', 'FolderController@delete')
        ->where('trashedFolder', '[0-9]+')->name('folders.delete');

    // Restore folder
    Route::post('/folders/{trashedFolder}/restore', 'FolderController@restore')
        ->where('trashedFolder', '[0-9]+')->name('folders.restore');

    /* Files */

    // Fetch files
    Route::get('/files/{file?}', 'FileController@fetch')
        ->where('file', '[0-9]+')->name('files.fetch');

    // Download file
    Route::get('/files/{file}/download', 'FileController@download')
        ->where('file', '[0-9]+')->name('files.download');

    // Store file
    Route::post('/files', 'FileController@store')->name('files.store');

    // Update file
    Route::patch('/files/{file}', 'FileController@update')
        ->where('file', '[0-9]+')->name('files.update');

    // Move file
    Route::patch('/files/{file}/move', 'FileController@move')
        ->where('file', '[0-9]+')->name('files.move');

    // Change Owner of a folder
    Route::patch('/files/{file}/chown', 'FileController@changeOwner')
        ->where('file', '[0-9]+')->name('files.chown');

    // Trash file
    Route::delete('/files/{file}', 'FileController@trash')
        ->where('file', '[0-9]+')->name('files.trash');

    // Delete file
    Route::delete('/files/{trashedFile}/force', 'FileController@delete')
        ->where('trashedFile', '[0-9]+')->name('files.delete');

    // Restore file
    Route::post('/files/{trashedFile}/restore', 'FileController@restore')
        ->where('trashedFile', '[0-9]+')->name('files.restore');
});
