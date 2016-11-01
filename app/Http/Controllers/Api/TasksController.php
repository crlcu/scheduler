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
     * Run the task.
     *
     * @param  int  $id
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
