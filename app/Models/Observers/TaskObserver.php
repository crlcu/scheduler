<?php

namespace App\Models\Observers;

use Auth;
use Cron\CronExpression;

class TaskObserver {

    public function creating($model)
    {
        if (!$model->is_one_time_only)
            {
                $cron = CronExpression::factory($model->cron_expression);
                $model->next_due = $cron->getNextRunDate()->format('Y-m-d H:i:s');
            }

            $model->user_id = Auth::id();

        return true;
    }

    public function updating($model)
    {
        if (!$model->is_one_time_only)
        {
            $cron = CronExpression::factory($model->cron_expression);
            $model->next_due = $cron->getNextRunDate()->format('Y-m-d H:i:s');
        }

        return true;
    }
}
