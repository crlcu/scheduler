<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Task;
use App\Models\TaskExecution;

class TaskExecutionsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function stop($id)
    {
        $execution = TaskExecution::findOrFail($id);

        $task = Task::forCurrentUser()
            ->findOrFail($execution->task_id);

        $execution->stop();

        return redirect()->back();
    }
}
