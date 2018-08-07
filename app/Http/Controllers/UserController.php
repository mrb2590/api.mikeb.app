<?php

namespace App\Http\Controllers;

use App\File;
use App\Status;
use App\Traits\PagingLimit;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
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

    /**
     * Store a user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('add_users')) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'slug' => str_slug(explode('@', $request->input('email'))[0], '-'),
            'password' => bcrypt($request->input('password')),
            'api_token' => str_random(60),
            'status_id' => Status::where('name', 'good')->where('type', 'user')->first()->id,
        ]);

        $user->assignRole($request->input('role'));

        return $user->load('roles', 'status');
    }

    /**
     * Update a user.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($request->user()->cannot('update_users')) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|string|exists:roles,name',
            'status' => [
                'nullable',
                'string',
                Rule::exists('statuses', 'name')->where('type', 'user'),
            ]
        ]);

        $data = $request->all();

        if ($request->has('password')) {
            $data['password'] = bcrypt($data['password']);
        }

        if ($request->has('email')) {
            $data['slug'] = str_slug(explode('@', $data['email'])[0], '-');
        }

        if ($request->has('status')) {
            $data['status_id'] = Status::where('name', $data['status'])->first()->id;
        }

        $user->fill($data)->save();

        if ($request->has('role')) {
            $user->roles()->detach();
            $user->assignRole($request->input('role'));
        }

        return $user->load('roles', 'status');
    }

    /**
     * Trash a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, User $user)
    {
        if ($request->user()->cannot('remove_users')) {
            abort(403, 'Unauthorized.');
        }

        $user->delete();

        return response('', 204);
    }

    /**
     * Delete a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User $trashedUser
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, User $trashedUser)
    {
        if ($request->user()->cannot('remove_users')) {
            abort(403, 'Unauthorized.');
        }

        $trashedUser->forceDelete();

        return response('', 204);
    }

    /**
     * Restore a trashed user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User $trashedUser
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, User $trashedUser)
    {
        if ($request->user()->cannot('remove_users')) {
            abort(403, 'Unauthorized.');
        }

        $trashedUser->restore();

        return response('', 204);
    }
}
