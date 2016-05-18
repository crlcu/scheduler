@extends('emails.layout')

@section('content')
<div class="column-top">&nbsp;</div>

<table class="contents">
    <tr>
        <td class="padded">
            <h1>{{ $task['name'] }}</h1>

            <p><em>{!! nl2br($task['command']) !!}</em></p>
            <p>Done in <em>{{ $task['last_run']['duration_for_humans'] }}</em></p>

            <p>{!! nl2br($task['last_run']['result']) !!}</p>
        </td>
    </tr>
    </tbody>
</table>

<div class="column-bottom">&nbsp;</div>

@endsection