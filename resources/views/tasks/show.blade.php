@extends('layouts.default')

@section('page-title')
    Tasks | {{ $task['name'] }} | Overview
@endsection

@section('content')
    @include('tasks.card', ['task' => $task, 'executions' => $executions])

    @include('tasks.history', ['task' => $task, 'executions' => $executions])
@endsection
