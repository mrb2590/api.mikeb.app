<?php

namespace App\Http\Controllers;

use App\Folder;
use App\File;
use App\Traits\PagingLimit;
use Illuminate\Http\Request;

class FolderController extends Controller
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
     * Fetch folders.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Folder $folder
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request, Folder $folder = null)
    {
        if ($request->user()->cannot('fetch_folders')) {
            abort(403, 'Unauthorized.');
        }

        if ($folder) {
            return $folder;
        }

        if ($request->user()->cannot('fetch_all_folders')) {
            abort(403, 'Unauthorized.');
        }

        $limit = $this->pagingLimit($request);

        $this->validate($request, [
            'parent_id' => 'nullable|integer|exists:folders,id',
            'owned_by_id' => 'nullable|integer|exists:users,id',
            'created_by_id' => 'nullable|integer|exists:users,id',
            'as_tree' => 'nullable|boolean',
        ]);

        $folders = Folder::select();

        if ($request->has('parent_id')) {
            $folders->where('parent_id', $request->input('parent_id'));
        }

        if ($request->has('owned_by_id')) {
            $folders->where('owned_by_id', $request->input('owned_by_id'));
        }

        if ($request->has('created_by_id')) {
            $folders->where('created_by_id', $request->input('created_by_id'));
        }

        if ($request->has('as_tree') && $request->input('as_tree')) {
            return $folders->whereNull('parent_id')->with('all_children')->paginate($limit);
        }

        return $folders->paginate($limit);
    }

    /**
     * Fetch folder with filesr.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Folder $folder
     * @return \Illuminate\Http\Response
     */
    public function fetchFiles(Request $request, Folder $folder)
    {
        if (($request->user()->cannot('fetch_all_files') ||
            $request->user()->cannot('fetch_all_folders')) ||
            ($request->user()->cannot('fetch_folders') ||
            $request->user()->cannot('fetch_files') &&
            $request->user()->doesNotOwn($folder)) 
        ) {
            abort(403, 'Unauthorized.');
        }

        $limit = $this->pagingLimit($request);

        $this->validate($request, [
            'parent_id' => 'nullable|integer|exists:folders,id',
            'owned_by_id' => 'nullable|integer|exists:users,id',
            'created_by_id' => 'nullable|integer|exists:users,id',
            'as_tree' => 'nullable|boolean',
        ]);

        return $folder->files()->paginate($limit);
    }

    /**
     * Store a folder.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('store_folders')) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:folders,id'
        ]);

        $folder = Folder::create([
            'name' => $request->input('name'),
            'disk' => File::$defaultDisk,
            'parent_id' => $request->input('parent_id'),
            'owned_by_id' => $request->user()->id,
            'created_by_id' => $request->user()->id,
        ]);

        return $folder->load('owned_by', 'created_by');
    }

    /**
     * Update a folder.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Folder $folder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Folder $folder)
    {
        if ($request->user()->cannot('edit_folders')) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'name' => 'nullable|string|max:255',
        ]);

        $folder->name = $request->input('name');

        $folder->save();

        return $folder;
    }

    /**
     * Move a folder.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Folder $folder
     * @return \Illuminate\Http\Response
     */
    public function move(Request $request, Folder $folder)
    {
        if ($request->user()->cannot('edit_folders')) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'parent_id' => 'required|integer|exists:folders,id',
        ]);

        $parent = Folder::find($request->input('parent_id'));

        if ($request->user()->cannot('edit_all_folders') &&
            ($request->user()->doesNotOwn($folder) ||
            $request->user()->doesNotOwn($parnet))
        ) {
            abort(403, 'Unauthorized.');
        }

        if ($parent->id === $folder->id) {
            abort(400, 'The folder cannot be its own parent.');
        }

        $folder->parent_id = $parent->id;

        if ($folder->owned_by_id != $parent->owned_by_id) {
            $folder->owned_by_id = $parent->owned_by_id;

            // Recursively replace owner of all children
            Folder::loopAllChildren($folder, function($child) use ($parent) {
                $child->owned_by_id = $parent->owned_by_id;
                $child->save();
            });
        }

        $folder->save();

        return $folder->load('owned_by');
    }

    /**
     * Change the owner of a folder.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Folder $folder
     * @return \Illuminate\Http\Response
     */
    public function changeOwner(Request $request, Folder $folder)
    {
        if ($request->user()->cannot('edit_all_folders')) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'owned_by_id' => 'required|integer|exists:users,id',
        ]);

        $folder->owned_by_id = $request->input('owned_by_id');

        // Recursively replace owner of all children
        Folder::loopAllChildren($folder, function($child) use ($request) {
            $child->owned_by_id = $request->input('owned_by_id');
            $child->save();
        });

        // Remove parent folder if it has a different owner
        $parent = $folder->parent()->first();
        if ($parent && $parent->owned_by_id !== $folder->owned_by_id) {
            $folder->parent_id = null;
        }

        $folder->save();

        return $folder->load('owned_by');
    }

    /**
     * Trash a folder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Folder $folder
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, Folder $folder)
    {
        if ($request->user()->cannot('remove_all_folders') &&
            ($request->user()->cannot('remove_folders') ||
            $request->user()->doesNotOwn($folder))
        ) {
            abort(403, 'Unauthorized.');
        }

        $folder->delete();

        return response('', 204);
    }

    /**
     * Delete a folder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Folder $trashedFolder
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Folder $trashedFolder)
    {
        if ($request->user()->cannot('remove_all_folders') &&
            ($request->user()->cannot('remove_folders') ||
            $request->user()->doesNotOwn($trashedFolder))
        ) {
            abort(403, 'Unauthorized.');
        }

        $trashedFolder->forceDelete();

        return response('', 204);
    }

    /**
     * Restore a trashed folder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Folder $trashedFolder
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, Folder $trashedFolder)
    {
        if ($request->user()->cannot('remove_all_folders') &&
            ($request->user()->cannot('remove_folders') ||
            $request->user()->doesNotOwn($trashedFolder))
        ) {
            abort(403, 'Unauthorized.');
        }

        $trashedFolder->restore();

        return response('', 204);
    }
}
