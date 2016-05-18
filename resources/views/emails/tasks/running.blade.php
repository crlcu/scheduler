@extends('emails.layout')

@section('content')
<div class="column-top">&nbsp;</div>

<table class="contents">
    <tr>
        <td class="padded">
            <h1>{{ $task['name'] }}</h1>

            <p><em>{!! nl2br($task['command']) !!}</em></p>

            <p>Started to run on {{ $task['last_run']['created_at'] }}</p>
        </td>
    </tr>
    </tbody>
</table>

<div class="column-bottom">&nbsp;</div>

@endsection