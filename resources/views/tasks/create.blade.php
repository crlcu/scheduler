@extends('layouts.default')

@section('content')
    {!! Form::open(['action' => 'TasksController@store', 'novalidate']) !!}
        @include('tasks.form')
    {!! Form::close() !!}
@endsection

@section('scripts')
@parent
<script type="text/javascript">
$(document).ready(function($) {
    var $ssh_details = $('.ssh');

    $('[name="Task[is_one_time_only]"]').on('change', function() {
        if (this.checked) {
            $('[name="Task[cron_expression]"]').closest('.input-field').addClass('hide');
            $('[name="Task[next_due]"]').closest('.input-field').removeClass('hide');
        } else {
            $('[name="Task[cron_expression]"]').closest('.input-field').removeClass('hide');
            $('[name="Task[next_due]"]').closest('.input-field').addClass('hide');
        }
    });

    $('[name="Task[is_via_ssh]"]').on('change', function() {
        if (this.checked) {
            $ssh_details.removeClass('hide');
        } else {
            $ssh_details.addClass('hide');
        }
    });
});
</script>
@endsection
