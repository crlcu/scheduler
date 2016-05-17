<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Task;
use App\Models\TaskNotification;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notification = TaskNotification::findOrFail($id);

        $task = Task::forCurrentUser()
            ->findOrFail($notification->task_id);

        return view('notifications.edit', [
            'notification' => $notification,
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
            'Notification.type'     => 'required',
            'Notification.status'   => 'required',
            'Notification.to'       => 'required',
            'Slack.username'        => 'required_if:Notification.type,slack',
            'Slack.channel'         => 'required_if:Notification.type,slack',
        ]);

        $notification = TaskNotification::findOrFail($id);

        $task = Task::forCurrentUser()
            ->findOrFail($notification->task_id);

        $notification->fill($request->input('Notification'));

        if ($request->input('Notification.type') == 'slack')
        {
            $notification->fill([
                'slack_config_json' => json_encode($request->input('Slack'))
            ]);            
        }

        $notification->save();

        return redirect()->action('TasksController@notifications', $task->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = TaskNotification::findOrFail($id);

        $task = Task::forCurrentUser()
            ->findOrFail($notification->task_id);

        $notification->delete();

        return redirect()->action('TasksController@notifications', $task->id);
    }
}
