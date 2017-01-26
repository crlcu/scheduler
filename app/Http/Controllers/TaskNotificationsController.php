<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Task;
use App\Models\TaskNotification;

class TaskNotificationsController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $notification = new TaskNotification();
        $task = Task::forCurrentUser()
            ->findOrFail($request->input('task_id'));

        return view('task_notifications.create', compact('notification', 'task'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'Notification.task_id'  => 'required',
            'Notification.type'     => 'required',
            'Notification.status'   => 'required',
            'Notification.to'       => 'required',
            'Slack.username'        => 'required_if:Notification.type,slack',
            'Slack.channel'         => ['required_if:Notification.type,slack', 'regex:/^#.*/'],
        ];

        if ($request->input('Notification.type') == 'mail')
        {
            $rules['Notification.to'] = ['required', 'email'];
        } 
        elseif ($request->input('Notification.type') == 'slack')
        {
            $rules['Notification.to'] = ['required', 'regex:/http(s)?:\/\/hooks\.slack\.com\/services\//i'];
        } 
        elseif ($request->input('Notification.type') == 'sms')
        {
            $rules['Notification.to'] = ['required', 'regex:/^\+(?:[0-9]?){6,14}[0-9]$/i'];
        }
        elseif ($request->input('Notification.type') == 'ping')
        {
            $rules['Notification.to'] = ['required', 'url'];
        }

        $this->validate($request, $rules);

        $task = Task::forCurrentUser()
            ->findOrFail($request->input('Notification.task_id'));

        $notification = new TaskNotification($request->input('Notification'));

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

        return view('task_notifications.edit', compact('notification', 'task'));
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
        if ($request->input('Notification.type') == 'mail')
        {
            $rules['Notification.to'] = ['required', 'email'];
        } 
        elseif ($request->input('Notification.type') == 'slack')
        {
            $rules['Notification.to'] = ['required', 'regex:/http(s)?:\/\/hooks\.slack\.com\/services\//i'];
        } 
        elseif ($request->input('Notification.type') == 'sms')
        {
            $rules['Notification.to'] = ['required', 'regex:/^\+(?:[0-9]?){6,14}[0-9]$/i'];
        }
        elseif ($request->input('Notification.type') == 'ping')
        {
            $rules['Notification.to'] = ['required', 'url'];
        }

        $this->validate($request, $rules);

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe(Request $request, $id)
    {
        $notification = TaskNotification::findOrFail($id);

        if ($request->input('key') == $notification->unsubscribe_id)
        {
            $notification->delete();
            return 'Unsubscribed';
        }

        return 'Invalid key';
    }
}
