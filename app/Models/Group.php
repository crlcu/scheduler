<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];


    /**
     * Relations
     */
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }


    /**
     * Methods
     */
    public function hasRole($role = null)
    {
        return ($this->roles->where('name', $role)->count() > 0);
    }
}
