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

        $addUsersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'add_users',
            'display_name' => 'Add Users',
            'description' => 'Add or invite new users.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $updateUsersPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'update_users',
            'display_name' => 'Update Users',
            'description' => 'Update user\'s information.',
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

        $fetchFilesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'fetch_files',
            'display_name' => 'Fetch Files',
            'description' => 'Fetch files.',
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

        $fetchAllDirectoriesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'fetch_all_directories',
            'display_name' => 'Fetch All Directories',
            'description' => 'Fetch all directories.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $removeAllDirectoriesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'remove_all_directories',
            'display_name' => 'Remove All Directories',
            'description' => 'Remove all directories.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $fetchDirectoriesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'fetch_directories',
            'display_name' => 'Fetch Directories',
            'description' => 'Fetch directories.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $storeDirectoriesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'store_directories',
            'display_name' => 'Store Directories',
            'description' => 'Store directories.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $removeDirectoriesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'remove_directories',
            'display_name' => 'Remove Directories',
            'description' => 'Remove directories.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $shareDirectoriesPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'share_directories',
            'display_name' => 'Share Directories',
            'description' => 'Share directories with public links.',
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
            'permission_id' => $addUsersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $updateUsersPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeUsersPermissionId,
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
            'permission_id' => $fetchAllDirectoriesPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeAllDirectoriesPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchDirectoriesPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $storeDirectoriesPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeDirectoriesPermissionId,
            'role_id' => $superRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $shareDirectoriesPermissionId,
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
            'permission_id' => $addUsersPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $updateUsersPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeUsersPermissionId,
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
            'permission_id' => $removeFilesPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $shareFilesPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchDirectoriesPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $storeDirectoriesPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeDirectoriesPermissionId,
            'role_id' => $adminRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $shareDirectoriesPermissionId,
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
            'permission_id' => $removeFilesPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $shareFilesPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchDirectoriesPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $storeDirectoriesPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $removeDirectoriesPermissionId,
            'role_id' => $memberRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $shareDirectoriesPermissionId,
            'role_id' => $memberRoleId
        ]);
        
        // Viewer Permissions
        DB::table('permission_role')->insert([
            'permission_id' => $fetchFilesPermissionId,
            'role_id' => $viewerRoleId
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $fetchDirectoriesPermissionId,
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
