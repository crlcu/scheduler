@extends('layouts.default')

@section('page-title')
    Tasks | Add
@endsection

@section('content')
    {!! Form::open(['action' => 'TasksController@store', 'novalidate']) !!}
        @include('tasks.form')
    {!! Form::close() !!}
@endsection
