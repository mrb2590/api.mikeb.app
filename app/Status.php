<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description', 'type'];

    /**
     * Set the status type.
     *
     * @param string $value
     */
    public function setStatusAttribute($value)
    {
        $statusTypes = ['user'];

        if (!in_array($value, $statusTypes)) {
            throw new \Exception('Invalid status type.');
        }

        $this->attributes['type'] = $value;
    }
}
