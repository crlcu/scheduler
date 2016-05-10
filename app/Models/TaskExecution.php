<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskExecution extends Model
{
	protected $dates = ['created_at', 'updated_at'];
	protected $fillable = ['task_id', 'status', 'result'];
    protected $appends = ['duration'];


    /**
     * Accessors & Mutators
     */
    public function getDurationAttribute($value)
    {
        return $this->updated_at->diffForHumans($this->created_at);
    }


    /**
     * Relations
     */
    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }
}
