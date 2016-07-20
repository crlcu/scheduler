<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use Venturecraft\Revisionable\RevisionableTrait;

use App\Models\Observers\UserObserver;

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
     * Initialize the observer
     * @return [UserObserver]
     */
    public static function boot()
    {
        parent::boot();

        parent::observe(new UserObserver);
    }
    
    /**
     * Relations
     */
    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    /**
     * Methods
     */
    public function hasRole($role)
    {
        return $this->group->hasRole($role);
    }
}
