@extends('layouts.default')

@section('content')
    <table class="bordered highlight condensed">
        <thead>
            <tr>
                <th width="45px"></th>
                <th>Name</th>
                <th>Type</th>
                <th>Average</th>
                <th>Next due</th>                
                <th class="center-align" width="70px">
                    <a href="{{ action('TasksController@create') }}" class="btn-floating waves-effect waves-light green" title="Add">
                        <i class="material-icons">add</i>
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
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
                    <td class="center-align">
                        <a href="{{ action('TasksController@edit', $task['id']) }}" class="btn-floating btn-small waves-effect waves-light blue" title="Edit">
                            <i class="material-icons">edit</i>
                        </a>
                        <a href="{{ action('TasksController@edit', $task['id']) }}" class="btn-floating btn-small waves-effect waves-light red" title="Remove">
                            <i class="material-icons">delete</i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
