@extends('layouts.default')

@section('content')
    @include('tasks.card', ['task' => $task])

    @include('tasks.history', ['task' => $task, 'executions' => $executions])
@endsection
