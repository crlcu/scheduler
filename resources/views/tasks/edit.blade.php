@extends('layouts.default')

@section('content')
    {!! Form::model($task, ['action' => ['TasksController@update', $task['id']], 'method' => 'put', 'novalidate']) !!}
        @include('tasks.form')
    {!! Form::close() !!}
@endsection
