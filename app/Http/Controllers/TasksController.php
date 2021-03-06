<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Artisan;
use Carbon\Carbon;

use App\Models\Task;
use App\Models\TaskExecution;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Task::forCurrentUser()
            ->with('executions', 'last_run');

        if ($q = $request->input('q'))
        {
            $query = $query->search($q, null, true, 1);
        }

        $tasks = $query->orderBy('is_enabled', 'desc')
            ->orderBy('next_due')
            ->paginate(10);

        return view('tasks.index', compact('tasks', 'q'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function timeline(Request $request)
    {
        $query = TaskExecution::forCurrentUser()
            ->with('task');

        $start = $request->input('start', new Carbon(config('charts.tasks.timeline_start')));
        $query = $query->startingAt($start);

        if ($end = $request->input('end'))
        {
            $query = $query->endingAt($end);
        }

        $executions = $query->orderBy('created_at', 'asc')
            ->get();            

        return view('tasks.timeline', compact('executions', 'start', 'end'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task();

        return view('tasks.create', compact('task'));
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
            'Task.name'                     => 'required',
            'Task.cron_expression'          => ['required_if:Task.is_one_time_only,0', 'cron_expression'],
            'Task.next_due'                 => ['required_if:Task.is_one_time_only,1', 'date_format:Y-m-d H:i:s'],
            'Task.command'                  => 'required',
            'SSH.host'                      => 'required_if:Task.is_via_ssh,1',
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
        $task = Task::forCurrentUser()
            ->findOrFail($id);

        $executions = $task->executions()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $start = new Carbon(config('charts.tasks.details_start'));

        $completed = $task->executions()
            ->with('task')
            ->startingAt($start)
            ->completed()
            ->get();

        $failed = $task->executions()
            ->with('task')
            ->startingAt($start)
            ->failed()
            ->get();

        return view('tasks.show', compact('task', 'executions', 'start', 'completed', 'failed'));
    }

    /**
     * Display notifications for specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function notifications($id)
    {
        $task = Task::forCurrentUser()
            ->findOrFail($id);

        return view('tasks.notifications', compact('task'));
    }

    /**
     * Run the task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function run($id)
    {
        $task = Task::forCurrentUser()
            ->findOrFail($id);

        Artisan::queue('run:task', ['task' => $id]);
        
        return redirect()->action('TasksController@show', $id);
    }

    /**
     * Run the task if token match.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ping(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        if ($request->input('token') != $task->token)
        {
            return response()
                ->json(['reason' => 'Invalid token.'])
                ->setStatusCode(401)
                ->setCallback($request->input('callback'));
        }

        Artisan::queue('run:task', ['task' => $id]);
        
        return response()
                ->json(['message' => 'Task was sent to the queue.'])
                ->setCallback($request->input('callback'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::forCurrentUser()
            ->findOrFail($id);

        return view('tasks.edit', compact('task'));
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
            'Task.name'                     => 'required',
            'Task.cron_expression'          => ['required_if:Task.is_one_time_only,0', 'cron_expression'],
            'Task.next_due'                 => ['required_if:Task.is_one_time_only,1', 'date_format:Y-m-d H:i:s'],
            'Task.command'                  => 'required',
            'SSH.host'                      => 'required_if:Task.is_via_ssh,1',
        ]);

        $task = Task::forCurrentUser()
            ->findOrFail($id);

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
     * Enable/Disable the task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function onoff(Request $request, $id)
    {
        $task = Task::forCurrentUser()
            ->findOrFail($id);

        $task->update(['is_enabled' => $request->input('Task.is_enabled')]);
        
        return redirect()->action('TasksController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::forCurrentUser()
            ->findOrFail($id);

        $task->delete();

        return redirect()->action('TasksController@index');
    }

    /**
     * Remove executions for the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function clear($id)
    {
        $task = Task::forCurrentUser()
            ->findOrFail($id);

        $task->executions()->delete();

        return redirect()->action('TasksController@show', $id);
    }
}
