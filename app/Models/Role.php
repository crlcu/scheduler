<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Venturecraft\Revisionable\RevisionableTrait;

use App\Models\Observers\RoleObserver;

class Role extends Model
{
    use SoftDeletes, RevisionableTrait;
    
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description'
    ];

    /**
     * Initialize the observer
     *
     * @return [RoleObserver]
     */
    public static function boot()
    {
        parent::boot();

        parent::observe(new RoleObserver);
    }

    /**
     * Relations
     */
    public function groups()
    {
        return $this->belongsToMany('App\Models\Group');
    }
}
