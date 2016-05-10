<div class="row">
    <div class="input-field col s12 m5">
        {!! Form::label('Task[name]', 'Name') !!}
        {!! Form::text('Task[name]', $task['name'], ['required' => true, 'autofocus']) !!}

        @if ($errors->has('Task.name'))
            <span class="red-text">{{ $errors->first('Task.name') }}</span>
        @endif
    </div>

    <div class="input-field col s12 m5">
        {!! Form::label('Task[start_at]', 'Start at') !!}
        {!! Form::text('Task[start_at]', $task['start_at'], ['provide' => 'datetimepicker', 'required' => true]) !!}

        @if ($errors->has('Task.start_at'))
            <span class="red-text">{{ $errors->first('Task.start_at') }}</span>
        @endif
    </div>

    <div class="col s12 m2">
        <p>
            {!! Form::hidden('Task[viaSSH]', 0) !!}
            {!! Form::checkbox('Task[viaSSH]', 1, $task['viaSSH'], ['id' => 'Task[viaSSH]']) !!}
            {!! Form::label('Task[viaSSH]', 'Via SSH') !!}
        </p>
    </div>
</div>

<div id="ssh" class="row {{ $task['viaSSH'] ? '' : 'hide' }}">
    <p><b>SSH Details</b></p>

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
    <div class="input-field col s12">
        {!! Form::label('Task[command]', 'Command') !!}
        {!! Form::textarea('Task[command]', $task['command'], ['class' => 'materialize-textarea', 'required' => true]) !!}

        @if ($errors->has('Task.command'))
            <span class="red-text">{{ $errors->first('Task.command') }}</span>
        @endif
    </div>
</div>

<div class="row">
    <div class="col s12">
        <p>
            {!! Form::hidden('Task[is_enabled]', 0) !!}
            {!! Form::checkbox('Task[is_enabled]', 1, $task['is_enabled'], ['id' => 'Task[is_enabled]']) !!}
            {!! Form::label('Task[is_enabled]', 'Enabled') !!}
        </p>
    </div>
</div>

<div class="row">
    <div class="col s12">
        <button type="submit" class="btn waves-effect waves-light green">
            <i class="material-icons right">done</i> Save
        </button>
    </div>
</div>
