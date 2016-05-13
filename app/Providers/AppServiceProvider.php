<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Validator;
use Cron\CronExpression;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
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
