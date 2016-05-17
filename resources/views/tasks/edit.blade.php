@extends('layouts.default')

@section('navbar-items')
    <li>
        <a href="{{ action('TasksController@show', $task['id']) }}" class="waves-effect"><i class="material-icons right">arrow_back</i> {{ $task['name'] }}</a>
    </li>
@endsection

@section('content')
    {!! Form::model($task, ['action' => ['TasksController@update', $task['id']], 'method' => 'put', 'novalidate']) !!}
        @include('tasks.form')
    {!! Form::close() !!}
@endsection
