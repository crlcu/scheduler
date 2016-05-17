@extends('layouts.default')

@section('content')
	{!! Form::open(['action' => 'NotificationsController@store', 'novalidate']) !!}
		{!! Form::hidden('Notification[task_id]', $task_id) !!}

        @include('notifications.form')
    {!! Form::close() !!}
@endsection
