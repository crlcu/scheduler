<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Venturecraft\Revisionable\RevisionableTrait;
use Sofa\Eloquence\Eloquence as SearchTrait;

use App\Models\Observers\Task as Observer;
use App\Util\CronSchedule;

use Auth;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Cron\CronExpression;
use Event;
use Exception;

class Task extends Model
{
    use SoftDeletes, RevisionableTrait, SearchTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'command', 'cron_expression', 'next_due', 'is_one_time_only', 'is_via_ssh', 'ssh_config_json', 'is_concurrent', 'is_enabled'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['average', 'average_for_humans', 'cron_for_humans', 'details', 'has_notifications', 'status_class', 'ssh', 'schedule'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name'      => 10,
        'next_due'  => 10,
    ];

    /**
     * Initialize the observer
     *
     * @return [App\Models\Observers\Task]
     */
    public static function boot()
    {
        parent::boot();

        parent::observe(new Observer);
    }

    /**
     * Accessors & Mutators
     */
    public function getAverageAttribute($value)
    {
        return 1;

        $executions = $this->executions()->completed()->get();
        
        if (!$executions->count())
        {
            return 0;
        }

        $total = $executions->sum(function($execution) {
            return $execution->updated_at->diffInSeconds($execution->created_at);
        });

        return sprintf('%.2f', $total / $executions->count());
    }

    public function getAverageForHumansAttribute($value)
    {
        $average = $this->average;

        $hours = (int)($average / 3600);
        $minutes = (int)(($average - $hours * 3600) / 60);
        $seconds = (int)($average - ($hours * 3600 + $minutes * 60));

        return CarbonInterval::hour($hours)->minutes($minutes)->seconds($seconds)->forHumans();
    }

    public function getCronForHumansAttribute($value)
    {
        $expression = $this->next_due;

        if (!$this->is_one_time_only)
        {
            $schedule = CronSchedule::fromCronString($this->cron_expression);
            $expression = $schedule->asNaturalLanguage();
        }

        return $expression;
    }

    public function getDetailsAttribute($value)
    {
        return $this->is_via_ssh ? json_decode($this->ssh_config_json, true) : ['run' => 'localy'];
    }

    public function getHasNotificationsAttribute($value)
    {
        return 1;
        
        return $this->notifications->count() > 0;
    }

    public function getStatusClassAttribute($value)
    {
        return $this->is_enabled ? $this->last_run && $this->last_run->status_class : 'grey lighten-4';
    }

    public function getSshAttribute($value)
    {
        return json_decode($this->ssh_config_json ? : '[]', true);
    }

    public function getScheduleAttribute($value)
    {
        if ($this->is_one_time_only)
        {
            return $this->next_due;
        }

        return $this->cron_expression;
    }

    public function getTypeAttribute($value)
    {
        return $this->is_via_ssh ? 'ssh' : 'process';
    }

    /**
     * Scopes
     */
    public function scopeForCurrentUser($query)
    {
        return $query;

        $user = Auth::user();

        if ($user->hasRole('is-admin'))
        {
            return $query;
        }

        return $query->where('user_id', '=', $user->id);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', '=', 1);
    }

    public function scopeIsDue($query)
    {
        return $query->where('next_due', '<', Carbon::now());
    }
}
