<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Venturecraft\Revisionable\RevisionableTrait;

use App\Models\Observers\Role as Observer;

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
     * @return [App\Models\Observers\Role]
     */
    public static function boot()
    {
        parent::boot();

        parent::observe(new Observer);
    }

    /**
     * Relations
     */
    public function groups()
    {
        return $this->belongsToMany('App\Models\Group');
    }
}
