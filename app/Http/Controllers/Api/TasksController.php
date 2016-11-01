<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Artisan;

use App\Models\Task;

class TasksController extends Controller
{
    /**
     * Enable/Disable a task.
     *
     * @param int $id Task id
     * @param array $Task[is_enabled] 0/1 depending if you want to disable/enable the task
     * @return \Illuminate\Http\Response
     */
    public function onoff($id, Request $request)
    {
        $task = Task::forCurrentUser()
            ->findOrFail($id);

        $task->update(['is_enabled' => $request->input('Task.is_enabled')]);
        
        return response()
            ->json([
                'success'   => true,
                'data'      => $task,
            ])
            ->setCallback($request->input('callback'));
    }

    /**
     * Run a task.
     *
     * @param int $id Task id
     * @return \Illuminate\Http\Response
     */
    public function run($id, Request $request)
    {
        $task = Task::forCurrentUser()
            ->findOrFail($id);

        Artisan::queue('run:task', ['task' => $id]);
        
        return response()
            ->json(['success' => true])
            ->setCallback($request->input('callback'));
    }
}
