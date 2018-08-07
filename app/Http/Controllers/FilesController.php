<?php

namespace App\Http\Controllers;

use App\File;
use App\Traits\PagingLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FilesController extends Controller
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
            return $file->load('uploaded_by', 'owned_by');
        }

        if ($request->user()->cannot('fetch_all_files')) {
            abort(403, 'Unauthorized.');
        }

        $limit = $this->pagingLimit($request);

        $this->validate($request, [
            'owned_by' => 'nullable|integer|exists:users,id',
            'uploaded_by' => 'nullable|integer|exists:users,id',
        ]);

        $files = File::with(['uploaded_by', 'owned_by']);

        if ($request->has('owned_by')) {
            $files->where('owned_by', $request->input('owned_by'));
        }

        if ($request->has('uploaded_by')) {
            $files->where('uploaded_by', $request->input('uploaded_by'));
        }

        return $files->paginate($limit);
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

        $this->validate($request, ['file' => 'required|file|max:20000000']);

        $disk = File::$defaultDisk;

        $filename = Str::random(40).'.'.$request->file('file')->getClientOriginalExtension();

        $path = Storage::disk($disk)
            ->putFileAs($request->user()->storage_dir, $request->file('file'), $filename);

        $originalFilename = $request->has('filename')
            ? $request->input('filename')
            : pathinfo($request->file('file')->getClientOriginalName())['filename'];

        $pathInfo = pathinfo($path);

        $file = File::create([
            'uploaded_by' => $request->user()->id,
            'owned_by' => $request->user()->id,
            'original_filename' => $originalFilename,
            'basename' => $pathInfo['basename'],
            'disk' => $disk,
            'path' => $path,
            'filename' => $pathInfo['filename'],
            'extension' => $pathInfo['extension'],
            'mime_type' => $request->file('file')->getMimeType(),
            'size' => Storage::disk($disk)->size($path),
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
        if ($request->user()->cannot('store_files')) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, ['filename' => 'required|string|max:255']);

        $file->original_filename = $request->input('filename');
        $file->save();

        return $file;
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
