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
     * Return user(s).
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request, User $user = null)
    {
        if ($request->route()->named('user.fetch')) {
            return $request->user()->load('roles', 'status');
        }

        if ($user) {
            if ($request->user()->isNot($user) && $request->user()->cannot('fetch_all_users')) {
                abort(403, 'Unauthorized.');
            }

            return $user->load('roles', 'status');
        }

        if ($request->user()->cannot('fetch_all_users')) {
            abort(403, 'Unauthorized.');
        }

        $limit = $this->pagingLimit($request);

        return User::with(['roles', 'status'])->paginate($limit);
    }























    // /**
    //  * Return all users or by id.
    //  *
    //  * @param  \Illuminate\Http\Request $request
    //  * @param  \App\User $user
    //  * @return \Illuminate\Http\Response
    //  */
    // public function fetchAllOrSingle(Request $request, User $user = null)
    // {
    //     if ($user) {
    //         if ($request->user()->cant('fetch_all_users') || $request->user()->isnot($user)) {
    //             abort(403, 'Unauthorized.');
    //         }

    //         // return $user->files()->
    //     }
    // }























    // /**
    //  * Return users.
    //  *
    //  * @param  \Illuminate\Http\Request $request
    //  * @param  \App\User $user
    //  * @return \Illuminate\Http\Response
    //  */
    // public function fetchs(Request $request, User $user = null)
    // {
    //     if ($request->route()->named('user.fetch')) {
    //         return $request->user()->load('roles', 'status');
    //     }

    //     if ($user && (
    //         $user->is($request->user()) ||
    //         $request->user()->can('fetch_all_users'))
    //     ) {
    //         return $user->load('roles', 'status');
    //     }

    //     if ($request->user()->cant('fetch_all_users')) {
    //         abort(403, 'Unauthorized.');
    //     }

    //     $limit = $this->pagingLimit($request);

    //     return User::with(['roles', 'status'])->paginate($limit);
    // }

    // *
    //  * Return a single user with their files.
    //  *
    //  * @param  \Illuminate\Http\Request $request
    //  * @param  \App\User $user
    //  * @param  string $disk
    //  * @return \Illuminate\Http\Response
     
    // public function fetchFiles(Request $request, $user = null, $disk = null)
    // {
    //     if ($request->user()->cant('fetch_files')) {
    //         abort(403, 'Unauthorized.');
    //     }

    //     $limit = $this->pagingLimit($request);

    //     if ($request->route()->named('user.fetch.files')) {
    //         if ($disk &&
    //             $disk != File::$defaultDisk &&
    //             $request->user()->cant('select_file_disk')
    //         ) {
    //             abort(403, 'Unauthorized.');
    //         } else {
    //             $disk = File::$defaultDisk;
    //         }

    //         return $request->user()->files()->fromDisk($disk)->paginate($limit);
    //     }

    //     if ($user && $request->user()->is($user) ) {
    //         if ($request->user()->cant('select_file_disk')) {
    //             abort(403, 'Unauthorized.');
    //         }
    //     } else {
    //         $disk = File::$defaultDisk;
    //     }

    //     return $request->user()->files()->fromDisk($disk)->paginate($limit);
    // }
}
