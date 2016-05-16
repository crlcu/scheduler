<h4 class="header">Task Details</h4>

<div class="row">
    <div class="input-field col s12 m4">
        {!! Form::label('Task[name]', 'Name') !!}
        {!! Form::text('Task[name]', $task['name'], ['required' => true, 'autofocus']) !!}

        @if ($errors->has('Task.name'))
            <span class="red-text">{{ $errors->first('Task.name') }}</span>
        @endif
    </div>

    <div class="input-field col s12 m2 {{ $task['is_one_time_only'] || old('Task.is_one_time_only') ? 'hide' : '' }}">
        {!! Form::label('Task[cron_expression]', 'Cron expression') !!}
        {!! Form::text('Task[cron_expression]', $task['cron_expression'], ['placeholder' => '* * * * * *', 'required' => true]) !!}

        @if ($errors->has('Task.cron_expression'))
            <span class="red-text">{{ $errors->first('Task.cron_expression') }}</span>
        @endif
    </div>
    
    <div class="input-field col s12 m2 {{ $task['is_one_time_only'] || old('Task.is_one_time_only') ? '' : 'hide' }}">
        {!! Form::label('Task[next_due]', 'Next due') !!}
        {!! Form::text('Task[next_due]', $task['next_due'], ['placeholder' => 'yyyy-mm-dd hh:mm:ss', 'provide' => 'datetimepicker', 'required' => true]) !!}

        @if ($errors->has('Task.next_due'))
            <span class="red-text">{{ $errors->first('Task.next_due') }}</span>
        @endif
    </div>

    <div class="col s6 m2">
        <p>
            {!! Form::hidden('Task[is_one_time_only]', 0) !!}
            {!! Form::checkbox('Task[is_one_time_only]', 1, $task['is_one_time_only'], ['id' => 'Task[is_one_time_only]']) !!}
            {!! Form::label('Task[is_one_time_only]', 'One time only') !!}
        </p>
    </div>

    <div class="col s6 m2">
        <p>
            {!! Form::hidden('Task[is_via_ssh]', 0) !!}
            {!! Form::checkbox('Task[is_via_ssh]', 1, $task['is_via_ssh'], ['id' => 'Task[is_via_ssh]']) !!}
            {!! Form::label('Task[is_via_ssh]', 'Via SSH') !!}
        </p>
    </div>

    <div class="col s12 m2">
        <p>
            {!! Form::hidden('Task[is_enabled]', 0) !!}
            {!! Form::checkbox('Task[is_enabled]', 1, $task['is_enabled'], ['id' => 'Task[is_enabled]']) !!}
            {!! Form::label('Task[is_enabled]', 'Enabled') !!}
        </p>
    </div>

    <div class="input-field col s12">
        {!! Form::label('Task[command]', 'Command') !!}
        {!! Form::textarea('Task[command]', $task['command'], ['class' => 'materialize-textarea', 'required' => true]) !!}

        @if ($errors->has('Task.command'))
            <span class="red-text">{{ $errors->first('Task.command') }}</span>
        @endif
    </div>
</div>

<h4 class="header ssh {{ $task['is_via_ssh'] || old('Task.is_via_ssh') ? '' : 'hide' }}">SSH Details</h4>

<div class="row ssh {{ $task['is_via_ssh'] || old('Task.is_via_ssh') ? '' : 'hide' }}">
    <div class="input-field col s12">
        {!! Form::label('SSH[host]', 'Host') !!}
        {!! Form::text('SSH[host]', isset($task['ssh']['host']) ? $task['ssh']['host'] : '') !!}

        @if ($errors->has('SSH.host'))
            <span class="red-text">{{ $errors->first('SSH.host') }}</span>
        @endif
    </div>

    <div class="input-field col s12 m6">
        {!! Form::label('SSH[username]', 'Username') !!}
        {!! Form::text('SSH[username]', isset($task['ssh']['username']) ? $task['ssh']['username'] : '') !!}

        @if ($errors->has('SSH.username'))
            <span class="red-text">{{ $errors->first('SSH.username') }}</span>
        @endif
    </div>

    <div class="input-field col s12 m6">
        {!! Form::label('SSH[password]', 'Password') !!}
        {!! Form::text('SSH[password]', isset($task['ssh']['password']) ? $task['ssh']['password'] : '') !!}

        @if ($errors->has('SSH.password'))
            <span class="red-text">{{ $errors->first('SSH.password') }}</span>
        @endif
    </div>

    <div class="input-field col s12 m6">
        {!! Form::label('SSH[keytext]', 'Key text') !!}
        {!! Form::text('SSH[keytext]', isset($task['ssh']['keytext']) ? $task['ssh']['keytext'] : '') !!}

        @if ($errors->has('SSH.keytext'))
            <span class="red-text">{{ $errors->first('SSH.keytext') }}</span>
        @endif
    </div>

    <div class="input-field col s12 m6">
        {!! Form::label('SSH[keyphrase]', 'Key phrase') !!}
        {!! Form::text('SSH[keyphrase]', isset($task['ssh']['keyphrase']) ? $task['ssh']['keyphrase'] : '') !!}

        @if ($errors->has('SSH.keyphrase'))
            <span class="red-text">{{ $errors->first('SSH.keyphrase') }}</span>
        @endif
    </div>

    <div class="input-field col s12 m6">
        {!! Form::label('SSH[agent]', 'Agent') !!}
        {!! Form::text('SSH[agent]', isset($task['ssh']['agent']) ? $task['ssh']['agent'] : 'Scheduler') !!}

        @if ($errors->has('SSH.agent'))
            <span class="red-text">{{ $errors->first('SSH.agent') }}</span>
        @endif
    </div>

    <div class="input-field col s12 m6">
        {!! Form::label('SSH[timeout]', 'Timeout') !!}
        {!! Form::text('SSH[timeout]', isset($task['ssh']['timeout']) ? $task['ssh']['timeout'] : 10) !!}

        @if ($errors->has('SSH.timeout'))
            <span class="red-text">{{ $errors->first('SSH.timeout') }}</span>
        @endif
    </div>
</div>

<div class="row">
    <div class="col s12">
        <p>
            {!! Form::hidden('Task[has_notifications]', 0) !!}
            {!! Form::checkbox('Task[has_notifications]', 1, $task['has_notifications'], ['id' => 'Task[has_notifications]']) !!}
            {!! Form::label('Task[has_notifications]', 'Send notifications') !!}
        </p>
    </div>
</div>

<h4 class="header notifications {{ $task['has_notifications'] || old('Task.has_notifications') ? '' : 'hide' }}">Notifications Details</h4>

<div class="row notifications {{ $task['has_notifications'] || old('Task.has_notifications') ? '' : 'hide' }}">
    <div class="input-field col s12 m6">
        {!! Form::label('Notification[running][email]', 'When task is running send me an email at') !!}
        {!! Form::text('Notification[running][email]', $task['running_notification']['email']) !!}

        @if ($errors->has('Notification.running.email'))
            <span class="red-text">{{ $errors->first('Notification.running.email') }}</span>
        @endif
    </div>

    <div class="input-field col s12 m6">
        {!! Form::label('Notification[failed][email]', 'When task failed send me an email at') !!}
        {!! Form::text('Notification[failed][email]', $task['failed_notification']['email']) !!}

        @if ($errors->has('Notification.failed.email'))
            <span class="red-text">{{ $errors->first('Notification.failed.email') }}</span>
        @endif
    </div>

    <div class="input-field col s12 m6">
        {!! Form::label('Notification[completed][email]', 'When task is completed send me an email at') !!}
        {!! Form::text('Notification[completed][email]', $task['completed_notification']['email']) !!}

        @if ($errors->has('Notification.completed.email'))
            <span class="red-text">{{ $errors->first('Notification.completed.email') }}</span>
        @endif
    </div>
</div>

<div class="row">
    <div class="col s12">
        <button type="submit" class="btn waves-effect waves-light green">
            <i class="material-icons right">done</i> Save
        </button>
    </div>
</div>

@section('scripts')
@parent
<script type="text/javascript">
$(document).ready(function($) {
    var $ssh_details = $('.ssh');
    var $notifications_details = $('.notifications');

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

    $('[name="Task[has_notifications]"]').on('change', function() {
        if (this.checked) {
            $notifications_details.removeClass('hide');
        } else {
            $notifications_details.addClass('hide');
        }
    });
});
</script>
@endsection
