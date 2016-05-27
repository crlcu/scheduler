@extends('layouts.default')

@section('navbar-items')
    <li>
        <a href="{{ action('TasksController@show', $task['id']) }}" class="waves-effect"><i class="material-icons right">arrow_back</i> {{ $task['name'] }}</a>
    </li>
@endsection

@section('content')
    {!! Form::model($notification, ['action' => ['NotificationsController@update', $notification['id']], 'method' => 'put', 'novalidate']) !!}
        @include('notifications.form')
    {!! Form::close() !!}
@endsection
