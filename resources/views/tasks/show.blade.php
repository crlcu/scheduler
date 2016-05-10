@extends('layouts.default')

@section('content')
    <a href="{{ action('TasksController@run', $task['id']) }}" title="Run"><i class="material-icons green-text text-accent-3">launch</i></a>
    
    <table class="bordered striped highlight">
        <thead>
            <tr>
                <th>Status</th>
                <th>Result</th>
                <th>Started at</th>
                <th>Done at</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($task['executions'] as $execution)
                <tr class="{{ $execution['status'] == 'failed' ? 'red lighten-5' : '' }}">
                    <td>{{ $execution['status'] }}</td>
                    <td>{!! nl2br($execution['result']) !!}</td>
                    <td>{{ $execution['created_at'] }}</td>
                    <td>
                        @if ($execution['is_running'])
                            <i class="material-icons" title="Running">autorenew</i>
                        @else
                            {{ $execution['updated_at'] }}
                        @endif
                    </td>
                    <td>
                        @if ($execution['is_running'])
                            <i class="material-icons" title="Running">autorenew</i>
                        @else
                            {{ $execution['duration'] }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
