@extends('layouts.default')

@section('content')
    <table class="bordered striped highlight">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Details</th>
                <th>Command</th>
                <th>Average</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td>{{ $task['name'] }}</td>
                    <td>{{ $task['type'] }}</td>
                    <td>
                        @foreach ($task['ssh'] as $key => $value)
                            <b>{{ $key }}:</b> {{ $value }}<br/>
                        @endforeach
                    </td>
                    <td>{{ $task['command'] }}</td>
                    <td>{{ $task['average'] }} seconds</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
