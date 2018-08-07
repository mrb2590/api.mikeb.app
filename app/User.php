<?php

namespace App;

use App\Traits\HasRoles;
use App\Traits\OwnsObject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, OwnsObject, Notifiable, SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'slug', 'password', 'api_token', 'status_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['storage_dir'];

    /**
     * Get the size in a readable format.
     *
     * @return string   
     */
    public function getStorageDirAttribute()
    {
        return 'user_'.$this->id;
    }

    /**
     * Get only public user information.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublicInfo($query)
    {
        return $query->select('id', 'first_name', 'last_name');
    }

    /**
     * Get the status of the user.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Get the files uploaded by this user.
     */
    public function files()
    {
        return $this->hasMany(File::class, 'uploaded_by');
    }
}
