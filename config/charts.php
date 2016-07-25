<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Charts settings
    |--------------------------------------------------------------------------
    |
    | This file is for storing the settings for charts.
    |
    */

    'tasks' => [
        // Any string recognised by Carbon as a date
        'timeline_start'    => env('CHARTS_TASKS_TIMELINE_START', 'last week'),

        // Any string recognised by Carbon as a date
        'details_start'     => env('CHARTS_TASKS_DETAILS_START', 'last week'),
    ]
];
