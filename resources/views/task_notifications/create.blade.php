@extends('layouts.default')

@section('page-title')
    Tasks | {{ $task['name'] }} | Notifications | Add
@endsection

@section('navbar-items')
    <li>
        <a href="{{ action('TasksController@show', $task['id']) }}" class="waves-effect"><i class="material-icons right">arrow_back</i> {{ $task['name'] }}</a>
    </li>
@endsection

@section('content')
	{!! Form::open(['action' => 'TaskNotificationsController@store', 'novalidate']) !!}
		{!! Form::hidden('Notification[task_id]', $task['id']) !!}

        @include('task_notifications.form')
    {!! Form::close() !!}
@endsection
