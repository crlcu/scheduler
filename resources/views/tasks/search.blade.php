@extends('layouts.default')

@section('content')
    <table class="bordered highlight condensed">
        <thead>
            <tr>
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
