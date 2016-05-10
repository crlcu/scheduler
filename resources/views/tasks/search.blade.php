@extends('layouts.default')

@section('content')
    <table class="bordered striped highlight">
        <thead>
            <tr>
                <th>Name</th>
                <th>Start at</th>
                <th>Type</th>
                <th>Details</th>
                <th>Command</th>
                <th>Average</th>
                <th width="150px">
                    <a href="{{ action('TasksController@create') }}" class="btn-floating waves-effect waves-light green" title="Add">
                        <i class="material-icons">add</i>
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr class="{{ $task['is_enabled'] ? '' : 'grey' }}" title="{{ $task['is_enabled'] ? '' : 'This task is disabled.' }}">
                    <td>{{ $task['name'] }}</td>
                    <td>{{ $task['start_at'] }}</td>
                    <td>{{ $task['type'] }}</td>
                    <td>
                        @foreach ($task['ssh'] as $key => $value)
                            <b>{{ $key }}:</b> {{ $value }}<br/>
                        @endforeach
                    </td>
                    <td>{{ $task['command'] }}</td>
                    <td>{{ $task['average'] }} seconds</td>
                    <td>
                        <a href="{{ action('TasksController@show', $task['id']) }}" class="btn-floating waves-effect waves-light green" title="View history">
                            <i class="material-icons">history</i>
                        </a> | 
                        <a href="{{ action('TasksController@edit', $task['id']) }}" class="btn-floating waves-effect waves-light blue" title="Edit">
                            <i class="material-icons">edit</i>
                        </a>
                        <a href="{{ action('TasksController@edit', $task['id']) }}" class="btn-floating waves-effect waves-light red" title="Remove">
                            <i class="material-icons">delete</i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
