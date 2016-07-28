@extends('layouts.default')

@section('page-title')
    Tasks
@endsection

@section('content')
    <div class="widget">
        <div class="header indigo lighten-5">
            <span class="title">Tasks</span>
        </div>
        <div class="content">
            <table class="bordered highlight condensed">
                <caption>
                    {!! Form::open(['method' => 'get']) !!}
                        <div class="file-field input-field">
                            <button class="btn-floating waves-effect waves-light red lighten-1 right" type="submit" name="action"><i class="material-icons">search</i></button>
                            <div class="file-path-wrapper">
                                {!! Form::text('q', $q, ['id' => 'q', 'placeholder' => 'Search ...', 'autocomplete' => 'off']) !!}
                            </div>
                        </div>
                    {!! Form::close() !!}
                </caption>
                <thead>
                    <tr>
                        <th width="45px"></th>
                        <th>Name</th>
                        <th>Average duration</th>
                        <th>Schedule</th>
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
                            <tr class="{{ $task['is_enabled'] ? ($task['last_run']['status'] == 'failed' ? 'red lighten-5' : '') : 'grey lighten-4' }}" title="{{ $task['is_enabled'] ? ($task['last_run']['status'] == 'failed' ? 'Last execution failed.' : '') : 'This task is disabled.' }}">
                                <td class="center-align">
                                    @if ($task['is_enabled'])
                                        <a href="{{ action('TasksController@disable', $task['id']) }}" class="btn-floating btn-small waves-effect waves-light red" title="Disable" onclick="return confirm('Disable?')">
                                            <i class="material-icons">pause_circle_filled</i>
                                        </a>
                                    @else
                                        <a href="{{ action('TasksController@enable', $task['id']) }}" class="btn-floating btn-small waves-effect waves-light green" title="Enable" onclick="return confirm('Enable?')">
                                            <i class="material-icons">play_circle_outline</i>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ action('TasksController@show', $task['id']) }}" title="View history">
                                        {{ $task['name'] }}
                                    </a>
                                </td>
                                <td>{{ $task['average_for_humans'] }}</td>
                                <td>
                                    <span title="{{ $task['cron_for_humans'] }}">{{ $task['schedule'] }}</span>
                                </td>
                                <td>{{ $task['next_due'] }}</td>
                            </tr>
                        @endforeach
                    @elseif($q)
                        <tr>
                            <td class="center-align" colspan="5">
                                No matching results found.
                            </td>
                        </tr>
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
