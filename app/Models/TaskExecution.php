<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskExecution extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
	protected $dates = ['created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
        'task_id', 'status', 'result'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['duration', 'duration_for_humans', 'is_running', 'status_icon'];


    /**
     * Accessors & Mutators
     */
    public function getDurationAttribute($value)
    {
        return $this->updated_at->diffInSeconds($this->created_at);
    }

    public function getDurationForHumansAttribute($value)
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
            'running'   => '<i class="material-icons orange-text animate-spin">sync</i>',
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
