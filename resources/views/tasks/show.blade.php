@extends('layouts.default')

@section('content')
    @include('tasks.card', ['task' => $task, 'executions' => $executions])

    @include('tasks.history', ['task' => $task, 'executions' => $executions])
@endsection
