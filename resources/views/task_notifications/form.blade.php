<div class="widget">
    <div class="header indigo lighten-5">
        <span class="title">Notification details</span>
    </div>
    <div class="content">
        <div class="row">
            <div class="col s3">
                {!! Form::radio('Notification[type]', 'mail', $notification['type'] == 'mail' || !$notification['type'], ['id' => 'Notification[type][mail]']) !!}
                {!! Form::label('Notification[type][mail]', 'Mail') !!}
            </div>
            <div class="col s3">
                {!! Form::radio('Notification[type]', 'slack', $notification['type'] == 'slack', ['id' => 'Notification[type][slack]']) !!}
                {!! Form::label('Notification[type][slack]', 'Slack') !!}
            </div>
            <div class="col s3">
                {!! Form::radio('Notification[type]', 'sms', $notification['type'] == 'sms', ['id' => 'Notification[type][sms]']) !!}
                {!! Form::label('Notification[type][sms]', 'SMS') !!}
            </div>
            <div class="col s3">
                {!! Form::radio('Notification[type]', 'ping', $notification['type'] == 'ping', ['id' => 'Notification[type][ping]']) !!}
                {!! Form::label('Notification[type][ping]', 'Ping') !!}
            </div>
        </div>

        <div class="row">
            <div class="col s12 m6 required">
                {!! Form::label('Notification[status]', 'When status is') !!}
                {!! Form::select('Notification[status]', ['running' => 'running', 'failed' => 'failed', 'interrupted' => 'interrupted', 'completed' => 'completed'], $notification['status'], ['class' => 'browser-default','required' => true]) !!}

                @if ($errors->has('Notification.status'))
                    <span class="red-text">{{ $errors->first('Notification.status') }}</span>
                @endif
            </div>

            <div class="input-field col s12 m6 required">
                {!! Form::label('Notification[to]', 'Send a notification to') !!}
                {!! Form::text('Notification[to]', $notification['to'], ['required' => true, 'autofocus']) !!}

                @if ($errors->has('Notification.to'))
                    <span class="red-text">{{ $errors->first('Notification.to') }}</span>
                @endif
            </div>

            <div class="col s12">
                {!! Form::hidden('Notification[with_result]', 0) !!}
                {!! Form::checkbox('Notification[with_result]', 1, $notification['with_result'], ['id' => 'Notification[with_result]']) !!}
                {!! Form::label('Notification[with_result]', 'Append the result when sending this notification') !!}
            </div>
        </div>
    </div>
    <div class="footer indigo lighten-5">
        Field marked with <span class="red-text">*</span> is required.
    </div>
</div>

<div id="slack" class="widget {{ $notification['is_via_slack'] || old('Notification.type') == 'slack' ? '' : 'hide' }}">
    <div class="header indigo lighten-5">
        <span class="title">Slack details</span>
    </div>
    <div class="content">
        <div class="row">
            <div class="input-field col s12 m6 required">
                {!! Form::label('Slack[username]', 'Username') !!}
                {!! Form::text('Slack[username]', isset($notification['slack']['username']) ? $notification['slack']['username'] : 'deploy') !!}

                @if ($errors->has('Slack.username'))
                    <span class="red-text">{{ $errors->first('Slack.username') }}</span>
                @endif
            </div>

            <div class="input-field col s12 m6 required">
                {!! Form::label('Slack[channel]', 'Channel') !!}
                {!! Form::text('Slack[channel]', isset($notification['slack']['channel']) ? $notification['slack']['channel'] : '#general') !!}

                @if ($errors->has('Slack.channel'))
                    <span class="red-text">{{ $errors->first('Slack.channel') }}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="footer indigo lighten-5">
        Field marked with <span class="red-text">*</span> is required.
    </div>
</div>

<div class="row">
    <div class="col s12">
        <button type="submit" class="btn waves-effect waves-light green right">
            <i class="material-icons left">done</i> Save
        </button>
    </div>
</div>

@section('scripts')
@parent
<script type="text/javascript">
$(document).ready(function($) {
    var $slack = $('#slack');

    $('[name="Notification[type]"]').on('change', function() {
        console.log(this.value == 'slack');

        if (this.value == 'slack') {
            $slack.removeClass('hide');
        } else {
            $slack.addClass('hide');
        }
    });
});
</script>
@endsection
