<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Venturecraft\Revisionable\RevisionableTrait;

use Auth;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Event;

use App\Events\TaskCompleted;
use App\Events\TaskFailed;
use App\Events\TaskInterrupted;

class TaskExecution extends Model
{
    use SoftDeletes, RevisionableTrait;
    
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
    protected $appends = ['duration', 'duration_for_humans', 'is_running', 'status_class', 'status_icon', 'status_title'];


    /**
     * Accessors & Mutators
     */
    public function getChartYAttribute()
    {
        $field = $this->task->chart_y;

        return $this->$field;
    }

    public function getDurationAttribute($value)
    {
        $start = $this->is_running ? Carbon::now() : $this->updated_at;

        return $start->diffInSeconds($this->created_at);
    }

    public function getDurationForHumansAttribute($value)
    {
        $start = $this->is_running ? Carbon::now() : $this->updated_at;

        $hours = $start->diffInHours($this->created_at);
        $minutes = $start->diffInMinutes($this->created_at) - ($hours * 60);
        $seconds = $start->diffInSeconds($this->created_at) - ($hours * 60 * 60) - ($minutes * 60);

        return CarbonInterval::hour($hours)->minutes($minutes)->seconds($seconds)->forHumans();
    }

    public function getIsRunningAttribute($value)
    {
        return $this->status == 'running';
    }

    public function getStatusClassAttribute($value)
    {
        $class = [
            'running'       => '',
            'completed'     => '',
            'failed'        => 'red lighten-5',
            'interrupted'   => 'orange lighten-5',
        ];

        return isset($class[$this->status]) ? $class[$this->status] : '';
    }

    public function getStatusIconAttribute($value)
    {
        $icons = [
            'running'       => '<i class="material-icons orange-text animate-spin">sync</i>',
            'completed'     => '<i class="material-icons green-text">done</i>',
            'failed'        => '<i class="material-icons red-text">error</i>',
            'interrupted'   => '<i class="material-icons orange-text">warning</i>',
        ];

        return isset($icons[$this->status]) ? $icons[$this->status] : '';
    }

    public function getStatusTitleAttribute($value)
    {
        $title = [
            'running'       => 'Execution is running.',
            'completed'     => 'Execution is now completed.',
            'failed'        => 'Execution failed.',
            'interrupted'   => 'Execution was interrupted.',
        ];

        return isset($title[$this->status]) ? $title[$this->status] : '';
    }

    /**
     * Scopes
     */
    public function scopeForCurrentUser($query)
    {
        return $query->whereHas('task', function ($query) {
            return $query->forCurrentUser();
        });
    }

    public function scopeStartingAt($query, $datetime)
    {
        return $query->where('created_at', '>=', $datetime);
    }

    public function scopeEndingAt($query, $datetime)
    {
        return $query->where('updated_at', '<=', $datetime);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', '=', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', '=', 'failed');
    }

    public function scopeInterrupted($query)
    {
        return $query->where('status', '=', 'interrupted');
    }

    public function scopeRunning($query)
    {
        return $query->where('status', '=', 'running');
    }


    /**
     * Relations
     */
    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }


    /**
     * Methods
     */
    public function done($status = 'completed')
    {
        $this->update([
            'status' => $status,
        ]);

        if ($status == 'completed')
        {
            Event::fire(new TaskCompleted($this->task));
        }
        elseif ($status == 'failed')
        {
            Event::fire(new TaskFailed($this->task));
        }
        elseif ($status == 'interrupted')
        {
            Event::fire(new TaskInterrupted($this->task));
        }
    }

    public function stop()
    {
        if (!$this->task->is_via_ssh)
        {
            $output = shell_exec(sprintf('kill -s TERM %s', $this->pid));
            $this->done('interrupted');
        }
    }
}
