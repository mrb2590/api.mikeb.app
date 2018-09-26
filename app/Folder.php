<?php

namespace App;

use App\Traits\HasChildren;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
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
        'parent_id' => 'integer',
        'created_by_id' => 'integer',
        'owned_by_id' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['server'];

    /**
     * The relationships to always load.
     *
     * @var array
     */
    protected $with = ['owned_by', 'created_by'];

    /**
     * Get the server info.
     *
     * @return array   
     */
    protected function getServerAttribute()
    {
        return [
            'storage' => [
                'total' => Server::totalSpace(),
                'free' => Server::freeSpace(),
                'used' => Server::usedSpace(),
            ]
        ];
    }

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
        return $this->hasMany(Folder::class, 'parent_id', 'id')->orderBy('name');
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
        return $this->hasMany(File::class, 'parent_id', 'id')->orderBy('display_filename');
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
            $folder->traverseAllFiles($closure);
        }
    }

    /**
     * Move this folder to another or to the root.
     */
    public function move($newParentFolder = null, $newOwner = null)
    {
        $parent = null;

        if ($newParentFolder) {
            $parent = Folder::find($newParentFolder->id);
        }

        if ($parent) {
            if ($parent->id == $this->id) {
                throw new \Exception('Cannot move folder to itself.');
            }

            $this->parent_id = $parent->id;
        } else {
            $this->parent_id = null;
        }

        $this->save();

        if (!$parent && !$newOwner) {
            throw new \Exception('Must pass new owner if moving to root.');
        }

        $newOwnerId = $parent ? $parent->owned_by_id : $newOwner->id;

        if ($this->owned_by_id !== $newOwnerId) {
            $this->traverseAllFiles(function($file) use ($newOwnerId) {
                $file->owned_by_id = $newOwnerId;
                $file->save();
            });
        }

        return true;
    }

    /**
     * Get the path of the folder
     *
     * @return string
     */
    public function getPath()
    {
        $parent = $this->parent;

        $path = $this->name.'/';

        while ($parent) {
            $path = $parent->name.'/'.$path;
            $parent = $parent->parent;
        }

        return $path;
    }
}
