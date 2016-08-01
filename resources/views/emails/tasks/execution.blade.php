@extends('emails.layout')

@section('content')
<h1>{{ $task['name'] }}</h1>

<hr>

<p>
    <em>{!! nl2br($task['command']) !!}</em><br><br>
    Done in <em>{{ $task['last_run']['duration_for_humans'] }}</em>
</p>

@if ($notification['with_result'])
    <hr>

    <p><strong>Result</strong></p>

    <pre>{!! nl2br($task['last_run']['result']) !!}</pre>
@endif
@endsection
