<?php

namespace App;

use App\Traits\HasChildren;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use HasChildren, SoftDeletes;

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
        'parent_id' => 'integer',
        'created_by_id' => 'integer',
        'owned_by_id' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'disk', 'parent_id', 'owned_by_id', 'created_by_id',
    ];

    /**
     * The relationships to always load.
     *
     * @var array
     */
    protected $with = ['owned_by', 'created_by'];

    /**
     * Get the parent folder.
     */
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    /**
     * Recursively get all parent folders.
     */
    public function all_parents()
    {
        return $this->parent()->with('all_parents');
    }

    /**
     * Get the child folders.
     */
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id', 'id');
    }

    /**
     * Recursively get all child folders.
     */
    public function all_children()
    {
        return $this->children()->with('all_children');
    }

    /**
     * Recursively get all child folders.
     */
    public function all_children_files()
    {
        return $this->load('files')->children()->with('all_children_files', 'files');
    }

    /**
     * Get the files in the folder.
     */
    public function files()
    {
        return $this->hasMany(File::class, 'folder_id', 'id');
    }

    /**
     * Get the owner of the folder.
     */
    public function owned_by()
    {
        return $this->belongsTo(User::class, 'owned_by_id')->publicInfo();
    }

    /**
     * Get the creator the folder.
     */
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id')->publicInfo();
    }

    /**
     * Recursively traverse all folders and files within this folder.
     */
    public function traverseAllFiles(\Closure $closure)
    {
        $closure($this);

        foreach ($this->files as $file) {
            $closure($file);
        }

        foreach ($this->children as $folder) {
            $closure($folder);

            $folder->traverseAllFiles($closure);
        }
    }
}
