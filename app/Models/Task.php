<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $appends = ['average', 'ssh'];


    /**
     * Accessors & Mutators
     */
    public function getAverageAttribute($value)
    {
        $total = $this->executions->sum(function($execution) {
            return $execution->updated_at->diffInSeconds($execution->created_at);
        });

        return sprintf('%.2f', $total / $this->executions->count());
    }

    public function getSshAttribute($value)
    {
        return $this->viaSSH ? json_decode($this->jsonSSH, true) : ['run' => 'localy'];
    }

    public function getTypeAttribute($value)
    {
        return $this->viaSSH ? 'ssh' : 'process';
    }


    /**
     * Relations
     */
    public function executions()
    {
        return $this->hasMany('App\Models\TaskExecution');
    }
}
