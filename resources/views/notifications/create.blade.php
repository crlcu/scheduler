@extends('layouts.default')

@section('navbar-items')
    <li>
        <a href="{{ action('TasksController@show', $task['id']) }}" class="waves-effect"><i class="material-icons right">arrow_back</i> {{ $task['name'] }}</a>
    </li>
@endsection

@section('content')
	{!! Form::open(['action' => 'NotificationsController@store', 'novalidate']) !!}
		{!! Form::hidden('Notification[task_id]', $task['id']) !!}

        @include('notifications.form')
    {!! Form::close() !!}
@endsection
