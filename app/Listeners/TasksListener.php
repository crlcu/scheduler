<?php

namespace App\Listeners;

use App\Events\TaskRunning;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TasksListener implements ShouldQueue
{
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\TaskRunning',
            'App\Listeners\TasksListener@onTaskRunning'
        );
    
        $events->listen(
            'App\Events\TaskFailed',
            'App\Listeners\TasksListener@onTaskFailed'
        );

        $events->listen(
            'App\Events\TaskCompleted',
            'App\Listeners\TasksListener@onTaskCompleted'
        );
    }

    /**
     * Handle task running events.
     */ 
    public function onTaskRunning($event)
    {
        $task = $event->task;
        $notifications = $task->notifications->where('status', 'running');

        foreach ($notifications as $notification)
        {
            $notification->send();
        }
    }

    /**
     * Handle task failed events.
     */ 
    public function onTaskFailed($event)
    {
        $task = $event->task;
        $notifications = $task->notifications->where('status', 'failed');

        foreach ($notifications as $notification)
        {
            $notification->send();
        }
    }

    /**
     * Handle task completed events.
     */ 
    public function onTaskCompleted($event)
    {
        $task = $event->task;
        $notifications = $task->notifications->where('status', 'completed');

        foreach ($notifications as $notification)
        {
            $notification->send();
        }
    }
}
