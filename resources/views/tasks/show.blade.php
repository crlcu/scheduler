@extends('layouts.default')

@section('content')  
    @include('tasks.card', ['task' => $task])

    <table class="bordered highlight condensed">
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
            @foreach ($executions as $execution)
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

    {!! $executions->links() !!}
@endsection
