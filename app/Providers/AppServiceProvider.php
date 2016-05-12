<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Validator;

use Cron\CronExpression;

use App\Models\Task;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Task::creating(function ($task) {
            //dd($task->next_due);
            
            $cron = CronExpression::factory($task->cron_expression);

            $task->next_due = $cron->getNextRunDate()->format('Y-m-d H:i:s');

            return true;
        });

        Task::updating(function ($task) {
            $cron = CronExpression::factory($task->cron_expression);

            $task->next_due = $cron->getNextRunDate()->format('Y-m-d H:i:s');

            return true;
        });

        Validator::extend('cron_expression', function($attribute, $value, $parameters, $validator) {
            try {
                CronExpression::factory($value);
            } catch (\InvalidArgumentException $e) {
                return false;
            }

            return true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
