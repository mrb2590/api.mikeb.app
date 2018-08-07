<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Directory extends Model
{
    use SoftDeletes;
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
        'created_by' => 'integer',
        'owned_by' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'disk', 'parent', 'owned_by', 'created_by',
    ];

    /**
     * Get the parent directory.
     */
    public function parent()
    {
        return $this->belongsTo(Directory::class, 'parent');
    }

    /**
     * Get all child directories.
     */
    public function allParents()
    {
        return $this->parent()->with('allParents');
    }

    /**
     * Get the child directories.
     */
    public function children()
    {
        return $this->hasMany(Directory::class, 'parent', 'id');
    }

    /**
     * Get all child directories.
     */
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Get all child directories.
     */
    public function allChildrenFiles()
    {
        return $this->children()->with(['allChildren', 'files']);
    }

    /**
     * Get the files in the directory.
     */
    public function files()
    {
        return $this->hasMany(File::class, 'directory', 'id');
    }

    /**
     * Get the owner of the directory.
     */
    public function owned_by()
    {
        return $this->belongsTo(User::class, 'owned_by')->publicInfo();
    }

    /**
     * Get the creator the directory.
     */
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by')->publicInfo();
    }
}
