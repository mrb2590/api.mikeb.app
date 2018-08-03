<?php

namespace App\Http\Controllers;

use App\File;
use App\Traits\PagingLimit;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use PagingLimit;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Return a single user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        return $request->user()->load('roles.permissions', 'status');
    }

    /**
     * Return all users.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function fetchAll(Request $request)
    {
        if ($request->user()->cant('fetch_all_users')) {
            abort(403, 'Unauthorized.');
        }

        $limit = $this->pagingLimit($request);

        return User::with(['roles.permissions', 'status'])->paginate($limit);
    }

    /**
     * Return a single user with their files.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $disk
     * @return \Illuminate\Http\Response
     */
    public function fetchUserFiles(Request $request, $disk = null)
    {
        if ($request->user()->cant('fetch_files')) {
            abort(403, 'Unauthorized.');
        }

        $limit = $this->pagingLimit($request);

        if ($disk) {
            if ($request->user()->cant('select_file_disk')) {
                abort(403, 'Unauthorized.');
            }
        } else {
            $disk = File::$defaultDisk;
        }

        return $request->user()->files()->fromDisk($disk)->paginate($limit);
    }
}
