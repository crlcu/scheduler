<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskExecution extends Model
{
	protected $dates = ['created_at', 'updated_at'];
	protected $fillable = ['task_id', 'status', 'result'];
    protected $appends = ['duration', 'is_running', 'status_icon'];


    /**
     * Accessors & Mutators
     */
    public function getDurationAttribute($value)
    {
        return $this->updated_at->diffForHumans($this->created_at);
    }

    public function getIsRunningAttribute($value)
    {
        return $this->status == 'running';
    }

    public function getStatusIconAttribute($value)
    {
        $icons = [
            'running'   => '<i class="material-icons orange-text">query_builder</i>',
            'completed' => '<i class="material-icons green-text">done</i>',
            'failed'    => '<i class="material-icons red-text">error</i>',
        ];

        return $icons[$this->status];
    }


    /**
     * Relations
     */
    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }
}
