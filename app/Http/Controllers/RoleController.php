<?php

namespace App\Http\Controllers;

use App\Role;
use App\Traits\PagingLimit;
use Illuminate\Http\Request;

class RoleController extends Controller
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
     * Return roles
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request, Role $role = null)
    {
        if ($role) {
            return $role;
        }

        $limit = $this->pagingLimit($request);

        return Role::paginate($limit);
    }

    /**
     * Return roles and their permissions
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function fetchPermissions(Request $request)
    {
        $limit = $this->pagingLimit($request);

        return Role::with('permissions')->paginate($limit);
    }
}
