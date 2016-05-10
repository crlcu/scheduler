@extends('layouts.default')

@section('content')
    {!! Form::model($task, ['action' => ['TasksController@update', $task['id']], 'method' => 'put', 'novalidate']) !!}
        @include('tasks.form')
    {!! Form::close() !!}
@endsection

@section('scripts')
@parent
<script type="text/javascript">
$(document).ready(function($) {
    var $ssh_details = $('#ssh');

    $('[name="Task[viaSSH]"]').on('change', function() {
        if (this.checked) {
            $ssh_details.removeClass('hide');
        } else {
            $ssh_details.addClass('hide');
        }
    });
});
</script>
@endsection
