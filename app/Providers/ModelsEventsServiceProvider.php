<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Auth;
use Cron\CronExpression;

use App\Models\Task;

class ModelsEventsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Task::creating(function ($task)
        {
            if (!$task->is_one_time_only)
            {
                $cron = CronExpression::factory($task->cron_expression);
                $task->next_due = $cron->getNextRunDate()->format('Y-m-d H:i:s');
            }

            $task->user_id = Auth::id();

            return true;
        });

        Task::updating(function ($task)
        {
            if (!$task->is_one_time_only)
            {
                $cron = CronExpression::factory($task->cron_expression);
                $task->next_due = $cron->getNextRunDate()->format('Y-m-d H:i:s');
            }

            return true;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
