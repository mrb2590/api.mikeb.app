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
        'size' => 'integer',
        'folder_id' => 'integer',
        'owned_by_id' => 'integer',
        'created_by_id' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'original_filename', 'basename', 'filename', 'extension', 'mime_type', 'path', 'size',
        'disk', 'folder_id', 'owned_by_id', 'created_by_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['size_readable'];

    /**
     * The relationships to always load.
     *
     * @var array
     */
    protected $with = ['owned_by', 'created_by'];

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
        $sizeReadable .= ' '.@$size[$factor];

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
     * Get the folder the file belongs to.
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    /**
     * Get the owner of the file.
     */
    public function owned_by()
    {
        return $this->belongsTo(User::class, 'owned_by_id')->publicInfo();
    }

    /**
     * Get the creator the file.
     */
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id')->publicInfo();
    }
}
