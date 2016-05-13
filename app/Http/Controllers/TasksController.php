<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Artisan;

use App\Models\Task;
use App\Models\TaskExecution;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::forCurrentUser()
            ->with('executions')
            ->orderBy('is_enabled', 'desc')
            ->orderBy('next_due')
            ->get();

        return view('tasks.search', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task();

        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'Task.name'             => 'required',
            'Task.cron_expression'  => ['required_if:Task.is_one_time_only,0', 'cron_expression'],
            'Task.next_due'         => ['required_if:Task.is_one_time_only,1', 'date_format:Y-m-d H:i:s'],
            'Task.command'          => 'required',
            'SSH.host'              => 'required_if:Task.is_via_ssh,1',
        ]);

        $task = new Task($request->input('Task'));

        if ($request->input('Task.is_via_ssh'))
        {
            $task->fill([
                'ssh_config_json' => json_encode($request->input('SSH'))
            ]);            
        }

        $task->save();

        return redirect()->action('TasksController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        $executions = TaskExecution::where('task_id', '=', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tasks.show', [
            'task'          => $task,
            'executions'    => $executions
        ]);
    }

    /**
     * Enable the task
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function enable($id)
    {
        $task = Task::find($id);

        # enable
        $task->update(['is_enabled' => 1]);
        
        return redirect()->action('TasksController@index');
    }

    /**
     * Disable the task
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disable($id)
    {
        $task = Task::find($id);

        # disable
        $task->update(['is_enabled' => 0]);
        
        return redirect()->action('TasksController@index');
    }

    /**
     * Run the task
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function run($id)
    {
        $task = Task::find($id);

        Artisan::call('run:task', ['task' => $id]);
        
        return redirect()->action('TasksController@show', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);

        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'Task.name'             => 'required',
            'Task.cron_expression'  => ['required_if:Task.is_one_time_only,0', 'cron_expression'],
            'Task.next_due'         => ['required_if:Task.is_one_time_only,1', 'date_format:Y-m-d H:i:s'],
            'Task.command'          => 'required',
            'SSH.host'              => 'required_if:Task.is_via_ssh,1',
        ]);

        $task = Task::find($id);
        $task->fill($request->input('Task'));

        if ($request->input('Task.is_via_ssh'))
        {
            $task->fill([
                'ssh_config_json' => json_encode($request->input('SSH'))
            ]);            
        }

        $task->save();

        return redirect()->action('TasksController@show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();

        return redirect()->action('TasksController@index');
    }
}
