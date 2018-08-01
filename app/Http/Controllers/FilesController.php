<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FilesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // ini_set('upload_max_filesize', '20000M');
        // ini_set('post_max_size', '20000M');
        // ini_set('max_execution_time', '3600');
        // ini_set('max_input_time', '3600');
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
            'bucket' => 'required|string|in:private,public',
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
    	if ($file) {
    		return $file->load('uploaded_by');
    	}

    	return File::paginate(20)->load('uploaded_by');
    }

    /**
     * Fetch files.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $bucket
     * @return \Illuminate\Http\Response
     */
    public function fetchBucket(Request $request, $bucket)
    {
    	return File::fromBucket($bucket)->paginate(20)->load('uploaded_by');
    }

    /**
     * Store a file upload.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        $path = $request->file('file')->store('files', $request->input('bucket'));

        $originalFilename = $request->has('filename')
        	? $request->input('filename')
        	: pathinfo($request->file('file')->getClientOriginalName())['filename'];

        $pathInfo = pathinfo($path);

        $file = File::create([
        	'uploaded_by' => $request->user()->id,
        	'original_filename' => $originalFilename,
        	'bucket' => $request->input('bucket'),
        	'path' => $path,
        	'filename' => $pathInfo['filename'],
        	'extension' => $pathInfo['extension'],
        	'mime_type' => $request->file('file')->getMimeType(),
        	'size' => Storage::disk($request->input('bucket'))->size($path),
        	'url' => Storage::disk($request->input('bucket'))->url($path),
        ]);

        return $file;
    }
}
