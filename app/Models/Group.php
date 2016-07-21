<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Venturecraft\Revisionable\RevisionableTrait;

use App\Models\Observers\GroupObserver;

class Group extends Model
{
    use SoftDeletes, RevisionableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Initialize the observer
     * @return [GroupObserver]
     */
    public static function boot()
    {
        parent::boot();

        parent::observe(new GroupObserver);
    }

    /**
     * Relations
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }


    /**
     * Methods
     */
    public function hasRole($role = null)
    {
        return ($this->roles->where('name', $role)->count() > 0);
    }
}
