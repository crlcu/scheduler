<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Artisan;
use App\Models\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::with('executions')->get();

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
        $task = new Task($request->input('Task'));
        $task->user_id = 1;//Auth::id();

        if ($request->input('Task.viaSSH'))
        {
            $task->fill([
                'jsonSSH' => json_encode($request->input('SSH'))
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
        $task = Task::with('executions')->find($id);

        return view('tasks.show', [
            'task' => $task,
        ]);
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
        $task = Task::find($id);
        $task->fill($request->input('Task'));

        if ($request->input('Task.viaSSH'))
        {
            $task->fill([
                'jsonSSH' => json_encode($request->input('SSH'))
            ]);            
        }

        $task->save();

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
        //
    }
}
