<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Venturecraft\Revisionable\RevisionableTrait;

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
     * Relations
     */
    public function groups()
    {
        return $this->belongsToMany('App\Models\Group');
    }
}
