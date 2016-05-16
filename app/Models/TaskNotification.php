<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskNotification extends Model
{
    protected $fillable = ['status', 'email'];


    /**
     * Relations
     */
    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }
}
