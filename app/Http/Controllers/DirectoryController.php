<?php

namespace App\Http\Controllers;

use App\Directory;
use App\File;
use App\Traits\PagingLimit;
use Illuminate\Http\Request;

class DirectoryController extends Controller
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
     * Fetch directories.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Directory $directory
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request, Directory $directory = null)
    {
        if ($request->user()->cannot('fetch_directories')) {
            abort(403, 'Unauthorized.');
        }

        if ($directory) {
            return $directory->load('owned_by', 'created_by');
        }

        if ($request->user()->cannot('fetch_all_directories')) {
            abort(403, 'Unauthorized.');
        }

        $limit = $this->pagingLimit($request);

        $this->validate($request, [
            'parent' => 'nullable|integer|exists:directories,id',
            'owned_by' => 'nullable|integer|exists:users,id',
            'created_by' => 'nullable|integer|exists:users,id',
        ]);

        $directories = Directory::with(['owned_by', 'created_by']);

        if ($request->has('owned_by')) {
            $directories->where('owned_by', $request->input('owned_by'));
        }

        if ($request->has('created_by')) {
            $directories->where('created_by', $request->input('created_by'));
        }

        if ($request->has('parent')) {
            $directories->where('parent', $request->input('parent'));
        }

        return $directories->paginate($limit);
    }

    /**
     * Store a directory.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('store_directories')) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'parent' => 'nullable|integer|exists:directories,id'
        ]);

        $directory = Directory::create([
            'name' => $request->input('name'),
            'disk' => File::$defaultDisk,
            'parent' => $request->input('parent'),
            'owned_by' => $request->user()->id,
            'created_by' => $request->user()->id,
        ]);

        return $directory->load('owned_by', 'created_by');
    }

    /**
     * Update a directory.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Directory $directory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Directory $directory)
    {
        if ($request->user()->cannot('store_directories')) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'name' => 'nullable|string|max:255',
            'parent' => 'nullable|integer|exists:directories,id'
        ]);

        $directory->fill($request->all())->save();

        return $directory->load('owned_by', 'created_by');
    }

    /**
     * Trash a directory.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Directory $directory
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, Directory $directory)
    {
        if ($request->user()->cannot('remove_all_directories') &&
            ($request->user()->cannot('remove_directories') ||
            $request->user()->doesNotOwn($directory))
        ) {
            abort(403, 'Unauthorized.');
        }

        $directory->delete();

        return response('', 204);
    }

    /**
     * Delete a directory.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Directory $trashedDirectory
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Directory $trashedDirectory)
    {
        if ($request->user()->cannot('remove_all_directories') &&
            ($request->user()->cannot('remove_directories') ||
            $request->user()->doesNotOwn($trashedDirectory))
        ) {
            abort(403, 'Unauthorized.');
        }

        $trashedDirectory->forceDelete();

        return response('', 204);
    }

    /**
     * Restore a trashed directory.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Directory $trashedDirectory
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, Directory $trashedDirectory)
    {
        if ($request->user()->cannot('remove_all_directories') &&
            ($request->user()->cannot('remove_directories') ||
            $request->user()->doesNotOwn($trashedDirectory))
        ) {
            abort(403, 'Unauthorized.');
        }

        $trashedDirectory->restore();

        return response('', 204);
    }
}
