<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesPermissionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['role_id', 'user_id']);
        });

        // Create roles
        $superRoleId = DB::table('roles')->insertGetId([
            'name' => 'super_user',
            'display_name' => 'Super User',
            'description' => 'A Super User can do anything.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $adminRoleId = DB::table('roles')->insertGetId([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Admins are basically Super User with few limitations.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $memberRoleId = DB::table('roles')->insertGetId([
            'name' => 'member',
            'display_name' => 'Member',
            'description' => 'Member can do file uploads and downloads.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $viewerRoleId = DB::table('roles')->insertGetId([
            'name' => 'viewer',
            'display_name' => 'Viewer',
            'description' => 'Viewers can only download files.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        // Create permissions
        $manageAPIClientsPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'manage_api_clients',
            'display_name' => 'Manage API Clients',
            'description' => 'Create/update/delete clients to consume the API.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $fetchAllUsersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'fetch_all_users',
            'display_name' => 'Fetch All Users',
            'description' => 'Fetch all users.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $editAllUsersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'edit_all_users',
            'display_name' => 'Edit All Users',
            'description' => 'Edit all users.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $removeAllUsersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'remove_all_users',
            'display_name' => 'Remove All Users',
            'description' => 'Remove all users.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $addUsersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'add_users',
            'display_name' => 'Add Users',
            'description' => 'Add or invite new users.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $editUsersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'edit_users',
            'display_name' => 'Edit Users',
            'description' => 'Edit users.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $removeUsersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'remove_users',
            'display_name' => 'Remove Users',
            'description' => 'Remove users.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $fetchAllFilesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'fetch_all_files',
            'display_name' => 'Fetch All Files',
            'description' => 'Fetch all files.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $removeAllFilesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'remove_all_files',
            'display_name' => 'Remove All Files',
            'description' => 'Remove all files.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $editAllFilesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'edit_all_files',
            'display_name' => 'Edit All Files',
            'description' => 'Edit all files.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $fetchFilesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'fetch_files',
            'display_name' => 'Fetch Files',
            'description' => 'Fetch files.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $editFilesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'edit_files',
            'display_name' => 'Edit Files',
            'description' => 'Edit files.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $storeFilesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'store_files',
            'display_name' => 'Store Files',
            'description' => 'Store files.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $removeFilesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'remove_files',
            'display_name' => 'Remove Files',
            'description' => 'Remove files.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $shareFilesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'share_files',
            'display_name' => 'Share Files',
            'description' => 'Share files with public links.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $fetchAllFoldersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'fetch_all_folders',
            'display_name' => 'Fetch All Folders',
            'description' => 'Fetch all folders.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $removeAllFoldersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'remove_all_folders',
            'display_name' => 'Remove All Folders',
            'description' => 'Remove all folders.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $editAllFoldersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'edit_all_folders',
            'display_name' => 'Edit All Folders',
            'description' => 'Edit all folders.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $fetchFoldersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'fetch_folders',
            'display_name' => 'Fetch Folders',
            'description' => 'Fetch folders.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $storeFoldersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'store_folders',
            'display_name' => 'Store Folders',
            'description' => 'Store folders.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $editFoldersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'edit_folders',
            'display_name' => 'Edit Folders',
            'description' => 'Edit folders.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $removeFoldersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'remove_folders',
            'display_name' => 'Remove Folders',
            'description' => 'Remove folders.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $shareFoldersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'share_folders',
            'display_name' => 'Share Folders',
            'description' => 'Share folders with public links.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $selectFileDiskPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'select_disk',
            'display_name' => 'Select Disk',
            'description' => 'Select a disk to store files other than default.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $fetchFileDiskPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'fetch__disk',
            'display_name' => 'Fetch Disk',
            'description' => 'Fetch files from a disk other than default.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        // Link permissions to roles

        // Super User Permissions
        DB::table('permission_role')->insert([
            'permission_id' => $manageAPIClientsPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchAllUsersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeAllUsersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $editAllUsersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $addUsersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $editUsersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchAllFilesPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeAllFilesPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $editAllFilesPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $editFilesPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchFilesPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $storeFilesPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeFilesPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $shareFilesPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchAllFoldersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeAllFoldersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $editAllFoldersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $editFoldersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchFoldersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $storeFoldersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeFoldersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $shareFoldersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $selectFileDiskPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchFileDiskPermissionId,
            'role_id' => $superRoleId
        ]);
        
        // Admin Permissions
        DB::table('permission_role')->insert([
            'permission_id' => $fetchAllUsersPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $addUsersPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $editUsersPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchFilesPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $storeFilesPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $editFilesPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeFilesPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $shareFilesPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchFoldersPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $editFoldersPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $storeFoldersPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeFoldersPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $shareFoldersPermissionId,
            'role_id' => $adminRoleId
        ]);
        
        // Member Permissions
        DB::table('permission_role')->insert([
            'permission_id' => $fetchFilesPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $storeFilesPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $editFilesPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeFilesPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $shareFilesPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchFoldersPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $editFoldersPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $storeFoldersPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeFoldersPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $shareFoldersPermissionId,
            'role_id' => $memberRoleId
        ]);
        
        // Viewer Permissions
        DB::table('permission_role')->insert([
            'permission_id' => $fetchFilesPermissionId,
            'role_id' => $viewerRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchFoldersPermissionId,
            'role_id' => $viewerRoleId
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
}
