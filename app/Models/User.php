<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use Venturecraft\Revisionable\RevisionableTrait;

class User extends Authenticatable
{
    use SoftDeletes, RevisionableTrait;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Relations
     */
    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }


    /**
     * Methods
     */
    public function hasRole($role)
    {
        return $this->group->hasRole($role);
    }
}
