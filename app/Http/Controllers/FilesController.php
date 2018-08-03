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
     * Get a validator for an incoming file upload request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'file' => 'required|file|max:20000000',
            'disk' => 'nullable|string|in:'.implode(',', File::$disks),
            'filename' => 'nullable|string|max:255',
        ]);
    }

    /**
     * Fetch files.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\file $file
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request, File $file = null)
    {
        if ($request->user()->cant('fetch_files')) {
            abort(403, 'Unauthorized.');
        }

    	if ($file) {
    		return $file->load('uploaded_by');
    	}

        if ($request->user()->cant('fetch_all_files')) {
            abort(403, 'Unauthorized.');
        }

        $limit = $this->pagingLimit($request);

    	return File::with('uploaded_by')->paginate($limit);
    }

    /**
     * Fetch files from disk.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $disk
     * @return \Illuminate\Http\Response
     */
    public function fetchDisk(Request $request, $disk)
    {
        if ($request->user()->cant('fetch_files_disk') ||
            $request->user()->cant('fetch_all_files')
        ) {
            abort(403, 'Unauthorized.');
        }

        $limit = $this->pagingLimit($request);

        return File::fromDisk($disk)->with('uploaded_by')->paginate($limit);
    }

    /**
     * Store a file upload.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->cant('store_files')) {
            abort(403, 'Unauthorized.');
        }

        if ($request->has('disk')) {
            if ($request->user()->cant('select_file_disk')) {
                abort(403, 'Unauthorized.');
            }

            $disk = $request->input('disk');
        } else {
            $disk = File::$defaultDisk;
        }

        $this->validator($request->all())->validate();

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
        	'url' => Storage::disk($disk)->url($path),
        ]);

        return $file;
    }

    /**
     * Delete a file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\file $file
     */
    public function delete(Request $request, File $file)
    {
        if ($request->user()->cant('remove_all_files') &&
            ($request->user()->cant('remove_files') ||
            $request->user()->doesntOwn($file))
        ) {
            abort(403, 'Unauthorized.');
        }

        $file->delete();
    }

    /**
     * Restore a file (if not permanently deleted).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $id
     */
    public function restore(Request $request, $id)
    {
        $file = File::withTrashed()->findOrFail($id);

        if ($request->user()->cant('remove_all_files') &&
            ($request->user()->cant('remove_files') ||
            $request->user()->doesntOwn($file))
        ) {
            abort(403, 'Unauthorized.');
        }

        $file->restore();
    }
}
