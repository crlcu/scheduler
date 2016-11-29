<?php

use Illuminate\Database\Seeder;

use App\Models\Task;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::create([
            'user_id'           => 2,
            'name'              => 'What date and time it is?',
            'command'           => 'date',
            'cron_expression'   => '* * * * *',
        ]);
    }
}
