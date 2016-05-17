<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Artisan;

use App\Models\Task;
use App\Models\TaskExecution;
use App\Models\TaskNotification;

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
            'Task.name'                     => 'required',
            'Task.cron_expression'          => ['required_if:Task.is_one_time_only,0', 'cron_expression'],
            'Task.next_due'                 => ['required_if:Task.is_one_time_only,1', 'date_format:Y-m-d H:i:s'],
            'Task.command'                  => 'required',
            'SSH.host'                      => 'required_if:Task.is_via_ssh,1',
            'Notification[]'                => 'required_if:Task.has_notifications,1',
            'Notification.running.email'    => 'email',
            'Notification.failed.email'     => 'email',
            'Notification.completed.email'  => 'email',
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

        $executions = TaskExecution::where('task_id', '=', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(100);

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
        $task = Task::forCurrentUser()
            ->findOrFail($id);

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
        $task = Task::forCurrentUser()
            ->findOrFail($id);

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
        $task = Task::forCurrentUser()
            ->findOrFail($id);

        Artisan::queue('run:task', ['task' => $id]);
        
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
        $task = Task::forCurrentUser()
            ->findOrFail($id);

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
            'Task.name'                     => 'required',
            'Task.cron_expression'          => ['required_if:Task.is_one_time_only,0', 'cron_expression'],
            'Task.next_due'                 => ['required_if:Task.is_one_time_only,1', 'date_format:Y-m-d H:i:s'],
            'Task.command'                  => 'required',
            'SSH.host'                      => 'required_if:Task.is_via_ssh,1',
            //'Notification[]'                => 'required_if:Task.has_notifications,1',
            'Notification.running.email'    => 'email',
            'Notification.failed.email'     => 'email',
            'Notification.completed.email'  => 'email',
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

        $notifications = [];

        if ($request->input('Task.has_notifications'))
        {
            foreach ($request->input('Notification') as $status => $notification)
            {
                if ($notification['email'])
                {
                    $notifications[] = TaskNotification::firstOrNew(['task_id' => $task->id, 'status' => $status, 'email' => $notification['email']]);
                }
                else
                {
                    $task->notifications()->where('status', '=', $status)
                        ->delete();
                }
            }
        }
        else
        {
            $task->notifications()
                ->delete();
        }

        if ($task->save())
        {
            $task->notifications()->saveMany($notifications);
        }

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
