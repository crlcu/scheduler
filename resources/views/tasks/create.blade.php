@extends('layouts.default')

@section('content')
    {!! Form::open(['action' => 'TasksController@store', 'novalidate']) !!}
        @include('tasks.form')
    {!! Form::close() !!}
@endsection
