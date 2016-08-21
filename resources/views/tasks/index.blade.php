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
                        <th>Name</th>
                        <th>Average duration</th>
                        <th>Schedule</th>
                        <th width="150px">Next due</th>
                        <th width="120px">
                            <a href="{{ action('TasksController@create') }}" class="btn-floating waves-effect waves-light green right" title="Add">
                                <i class="material-icons">add</i>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($tasks))
                        @foreach ($tasks as $task)
                            <tr class="{{ $task['status_class'] }}" title="{{ $task['last_run']['status_title'] }}">
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
                                <td>
                                    {!! Form::model($task, ['action' => ['TasksController@onoff', $task['id']], 'method' => 'put', 'novalidate']) !!}
                                        <!-- Switch -->
                                        <div class="switch" title="{{ $task['is_enabled'] ? 'This task is enabled' : 'This task is disabled' }}">
                                            <label>
                                                Off
                                                {!! Form::hidden('Task[is_enabled]', 0) !!}
                                                {!! Form::checkbox('Task[is_enabled]', 1, $task['is_enabled']) !!}
                                                <span class="lever"></span>
                                                On
                                            </label>
                                        </div>
                                    {!! Form::close() !!}
                                </td>
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

@section('scripts')
@parent
<script type="text/javascript">
$(document).ready(function($) {
    $('[name="Task[is_enabled]"]').on('change', function(e) {
        $(this).closest('form').submit();
    });
});
</script>
@endsection
