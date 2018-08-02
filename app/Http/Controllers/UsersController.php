<?php

namespace App\Http\Controllers;

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
        return $request->user()->with('roles')->get();
    }

    /**
     * Return all users.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function fetchAll(Request $request)
    {
        if (!$request->user()->can('fetch_users')) {
            abort(403, 'Unauthorized.');
        }

        $limit = $this->pagingLimit($request);

        return User::with('roles')->paginate($limit);
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
        $limit = $this->pagingLimit($request);

        if ($disk) {
            return $request->user()->files()->fromDisk($disk)->paginate($limit);
        }

        return $request->user()->files()->paginate($limit);
    }
}
