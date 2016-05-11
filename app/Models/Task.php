<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;
use Cron\CronExpression;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'command', 'cron_expression', 'next_due', 'viaSSH', 'jsonSSH', 'is_enabled'];
    protected $appends = ['average', 'details', 'ssh'];


    /**
     * Accessors & Mutators
     */
    public function getAverageAttribute($value)
    {
        if (!$this->executions->count())
        {
            return '0';
        }

        $total = $this->executions->sum(function($execution) {
            return $execution->updated_at->diffInSeconds($execution->created_at);
        });

        return sprintf('%.2f', $total / $this->executions->count());
    }

    public function getDetailsAttribute($value)
    {
        return $this->viaSSH ? json_decode($this->jsonSSH, true) : ['run' => 'localy'];
    }

    public function getSshAttribute($value)
    {
        return json_decode($this->jsonSSH ? : '[]', true);
    }

    public function getTypeAttribute($value)
    {
        return $this->viaSSH ? 'ssh' : 'process';
    }


    /**
     * Scopes
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', '=', 1);
    }

    public function scopeIsDue($query)
    {
        return $query->where('next_due', '<', Carbon::now());
    }


    /**
     * Relations
     */
    public function executions()
    {
        return $this->hasMany('App\Models\TaskExecution');
    }


    /**
     * Methods
     */
    public function updateNextDue()
    {
        $cron = CronExpression::factory($this->cron_expression);

        return $this->update(['next_due' => $cron->getNextRunDate()->format('Y-m-d H:i:s')]);
    }
}
