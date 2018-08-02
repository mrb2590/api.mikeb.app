<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
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
        return $request->user()->load('files');
    }

    /**
     * Return a single user with their files.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $disk
     * @return \Illuminate\Http\Response
     */
    public function fetchUserFiles(Request $request, $disk)
    {
        if ($disk) {
            return $request->user()->files()->fromDisk($disk)->paginate(20);
        }

        return $request->user()->files()->paginate(20);
    }
}
