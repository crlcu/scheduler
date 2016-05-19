@extends('layouts.default')

@section('content')
    <div class="widget">
        <div class="header indigo lighten-5">
            <span class="title">Tasks</span>
        </div>
        <div class="content">
            <table class="bordered highlight condensed">
                <thead>
                    <tr>
                        <th width="45px"></th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Average</th>
                        <th width="150px">
                            Next due
                            <a href="{{ action('TasksController@create') }}" class="btn-floating waves-effect waves-light green right" title="Add">
                                <i class="material-icons">add</i>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($tasks))
                        @foreach ($tasks as $task)
                            <tr class="{{ $task['is_enabled'] ? '' : 'grey lighten-4' }}" title="{{ $task['is_enabled'] ? '' : 'This task is disabled.' }}">
                                <td class="center-align">
                                    @if ($task['is_enabled'])
                                        <a href="{{ action('TasksController@disable', $task['id']) }}" class="btn-floating btn-small waves-effect waves-light red" title="Disable">
                                            <i class="material-icons">pause_circle_filled</i>
                                        </a>
                                    @else
                                        <a href="{{ action('TasksController@enable', $task['id']) }}" class="btn-floating btn-small waves-effect waves-light green" title="Enable">
                                            <i class="material-icons">play_circle_outline</i>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ action('TasksController@show', $task['id']) }}" title="View history">
                                        {{ $task['name'] }}
                                    </a>
                                </td>
                                <td>{{ $task['type'] }}</td>
                                <td>{{ $task['average'] }} seconds</td>
                                <td>{{ $task['next_due'] }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="center-align" colspan="5">
                                <a href="{{ action('TasksController@create') }}" class="btn waves-effect waves-light green" title="Add">
                                    <i class="material-icons left">add</i> Add your first task
                                </a>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="footer indigo lighten-5">
            {!! $tasks->links() !!}
        </div>
    </div>
@endsection
