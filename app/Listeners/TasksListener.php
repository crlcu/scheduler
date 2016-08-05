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
            'App\Events\TaskInterrupted',
            'App\Listeners\TasksListener@onTaskInterrupted'
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
        $notifications = $task->notifications()->running()->get();

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
        $notifications = $task->notifications()->failed()->get();

        foreach ($notifications as $notification)
        {
            $notification->send();
        }
    }

    /**
     * Handle task interrupted events.
     */ 
    public function onTaskInterrupted($event)
    {
        $task = $event->task;
        $notifications = $task->notifications()->interrupted()->get();

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
        $notifications = $task->notifications()->completed()->get();

        foreach ($notifications as $notification)
        {
            $notification->send();
        }
    }
}
