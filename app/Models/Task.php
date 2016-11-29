<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Venturecraft\Revisionable\RevisionableTrait;

use App\Models\Observers\Task as Observer;

class Task extends Model
{
    use SoftDeletes, RevisionableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'command', 'cron_expression', 'next_due', 'is_one_time_only', 'is_via_ssh', 'ssh_config_json', 'is_concurrent', 'is_enabled'
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
}
