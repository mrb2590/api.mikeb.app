<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    /**
     * The default disk to store files.
     *
     * @static string
     */
    public static $defaultDisk = 'private';

    /**
     * All disks to store files.
     *
     * @static string
     */
    public static $disks = ['private', 'public'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'uploaded_by' => 'integer',
        'size' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uploaded_by', 'owned_by', 'original_filename', 'basename', 'filename', 'extension',
        'mime_type', 'path', 'size', 'disk', 'url',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['size_readable'];

    /**
     * Get the size in a readable format.
     *
     * @return string   
     */
    public function getSizeReadableAttribute()
    {
        $decimals = '2';
        $size = ['B','KB','MB','GB','TB','PB','EB','ZB','YB'];

        $factor = floor((strlen($this->size) - 1) / 3);

        $sizeReadable = sprintf("%.2f", $this->size / pow(1024, $factor));
        $sizeReadable .= ' ' . @$size[$factor];

        return $sizeReadable;
    }

    /**
     * Get files froma a disk.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $disk
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromDisk($query, $disk)
    {
        return $query->where('disk', $disk);
    }

    /**
     * Get the owner of the file.
     */
    public function owned_by()
    {
        return $this->belongsTo(User::class, 'owned_by')->publicInfo();
    }

    /**
     * Get the user who uploaded the file.
     */
    public function uploaded_by()
    {
        return $this->belongsTo(User::class, 'uploaded_by')->publicInfo();
    }
}
