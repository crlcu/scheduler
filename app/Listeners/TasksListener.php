<?php

namespace App\Listeners;

use App\Events\TaskRunning;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Log;
use Mail;

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
        if ($event->task->running_notification)
        {
            $task = $event->task;

            Mail::queue('emails.tasks.running', ['task' => $task], function ($mail) use($task) {
                $mail->to($task->running_notification->email)
                    ->subject(sprintf('"%s" has started to run', $task->name));
            });
        }
    }

    /**
     * Handle task failed events.
     */ 
    public function onTaskFailed($event)
    {
        if ($event->task->failed_notification)
        {
            $task = $event->task;

            Mail::queue('emails.tasks.execution', ['task' => $task], function ($mail) use($task) {
                $mail->to($task->failed_notification->email)
                    ->subject(sprintf('Result for "%s" task - %s', $task->name, $task->last_run->status));
            });
        }
    }

    /**
     * Handle task completed events.
     */ 
    public function onTaskCompleted($event)
    {
        if ($event->task->completed_notification)
        {
            $task = $event->task;

            Mail::queue('emails.tasks.execution', ['task' => $task], function ($mail) use($task) {
                $mail->to($task->completed_notification->email)
                    ->subject(sprintf('Result for "%s" task - %s', $task->name, $task->last_run->status));
            });
        }
    }
}
