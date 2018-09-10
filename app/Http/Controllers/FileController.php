<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use App\Traits\PagingLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileController extends Controller
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
     * Fetch file(s).
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\File $file
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request, File $file = null)
    {
        if ($request->user()->cannot('fetch_files')) {
            abort(403, 'Unauthorized.');
        }

        if ($file) {
            return $file;
        }

        $limit = $this->pagingLimit($request);

        $this->validate($request, [
            'folder_id' => 'nullable|integer',
            'owned_by_id' => 'nullable|integer|exists:users,id',
            'created_by_id' => 'nullable|integer|exists:users,id',
        ]);

        $files = File::select();

        if ($request->has('folder_id')) {
            if ($request->input('folder_id') == 0) {
                $files->whereNull('folder_id');
            } else {
                $files->where('folder_id', $request->input('folder_id'));
            }
        }

        if ($request->has('created_by_id')) {
            $files->where('created_by_id', $request->input('created_by_id'));
        }

        if ($request->has('owned_by_id')) {
            if ($request->user()->cannot('fetch_all_files') &&
                $request->user()->id != $file->owned_by_id
            ) {
                abort(403, 'Unauthorized.');
            }

            $files->where('owned_by_id', $request->input('owned_by_id'));
        } else {
            $files->where('owned_by_id', $request->user()->id);
        }

        return $files->paginate($limit);
    }

    /**
     * Download file.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\File $file
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, File $file)
    {
        if ($request->user()->cannot('fetch_all_files') &&
            ($request->user()->cannot('fetch_files') ||
            $request->user()->doesNotOwn($file))
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, ['encoded' => 'nullable|boolean']);

        if ($request->input('encoded')) {
            return [
                'filename' => $file->original_filename.$file->extension,
                'file' => base64_encode(Storage::disk($file->disk)->get($file->path))
            ];
        }

        return response()->download(
            storage_path('app/'.$file->disk.'/'.$file->path),
            $file->original_filename.'.'.$file->extension
        );
    }

    /**
     * Store a file.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('store_files')) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'file' => 'required|file|max:20000000',
            'folder_id' => 'nullable|integer|exists:folders,id', // To do: make sure user owns dir
        ]);

        if ($request->has('folder_id')) {
            $folder = Folder::find($request->input('folder_id'));

            if ($request->user()->doesNotOwn($folder)) {
                abort(403, 'Unauthorized.');
            }
        }

        $disk = File::$defaultDisk;

        $filename = Str::random(40).'.'.$request->file('file')->getClientOriginalExtension();

        $path = Storage::disk($disk)
            ->putFileAs($request->user()->storage_dir, $request->file('file'), $filename);

        $originalFilename = $request->has('filename')
            ? $request->input('filename')
            : pathinfo($request->file('file')->getClientOriginalName())['filename'];

        $pathInfo = pathinfo($path);

        $file = File::create([
            'original_filename' => $originalFilename,
            'basename' => $pathInfo['basename'],
            'disk' => $disk,
            'path' => $path,
            'filename' => $pathInfo['filename'],
            'extension' => $pathInfo['extension'],
            'mime_type' => $request->file('file')->getMimeType(),
            'size' => Storage::disk($disk)->size($path),
            'folder_id' => $request->input('folder_id'),
            'owned_by_id' => $request->user()->id,
            'created_by_id' => $request->user()->id,
        ]);

        return $file;
    }

    /**
     * Update a file.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\File $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        if ($request->user()->cannot('edit_all_files') &&
            ($request->user()->cannot('edit_files') ||
            $request->user()->doesNotOwn($file))
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, ['filename' => 'required|string|max:255']);

        $file->original_filename = $request->input('filename');
        $file->save();

        return $file;
    }

    /**
     * Move a file.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\File $file
     * @return \Illuminate\Http\Response
     */
    public function move(Request $request, File $file)
    {
        if ($request->user()->cannot('edit_files')) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'folder_id' => 'required|integer|exists:folders,id',
        ]);

        $folder = Folder::find($request->input('folder_id'));

        if ($request->user()->cannot('edit_all_files') &&
            ($request->user()->doesNotOwn($file) ||
            $request->user()->doesNotOwn($folder))
        ) {
            abort(403, 'Unauthorized.');
        }

        $file->folder_id = $folder->id;

        if ($file->owned_by->doesNotOwn($folder)) {
            $file->owned_by_id = $folder->owned_by_id;
        }

        $file->save();

        return $file->load('owned_by');
    }

    /**
     * Change the owner of a file.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\File $file
     * @return \Illuminate\Http\Response
     */
    public function changeOwner(Request $request, File $file)
    {
        if ($request->user()->cannot('edit_all_folders')) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'owned_by_id' => 'required|integer|exists:users,id',
        ]);

        $file->owned_by_id = $request->input('owned_by_id');

        // Remove parent folder if it has a different owner
        $folder = $file->folder()->first();
        if ($folder && $file->owned_by->doesNotOwn($folder)) {
            $file->folder_id = null;
        }

        $file->save();

        return $file->load('owned_by');
    }

    /**
     * Trash a file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\File $file
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, File $file)
    {
        if ($request->user()->cannot('remove_all_files') &&
            ($request->user()->cannot('remove_files') ||
            $request->user()->doesNotOwn($file))
        ) {
            abort(403, 'Unauthorized.');
        }

        $file->delete();

        return response('', 204);
    }

    /**
     * Delete a file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\File $trashedFile
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, File $trashedFile)
    {
        if ($request->user()->cannot('remove_all_files') &&
            ($request->user()->cannot('remove_files') ||
            $request->user()->doesNotOwn($trashedFile))
        ) {
            abort(403, 'Unauthorized.');
        }

        Storage::disk(File::$defaultDisk)->delete($trashedFile->path);

        $trashedFile->forceDelete();

        return response('', 204);
    }

    /**
     * Restore a trashed file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\File $trashedFile
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, File $trashedFile)
    {
        if ($request->user()->cannot('remove_all_files') &&
            ($request->user()->cannot('remove_files') ||
            $request->user()->doesNotOwn($trashedFile))
        ) {
            abort(403, 'Unauthorized.');
        }

        $trashedFile->restore();

        return response('', 204);
    }
}
