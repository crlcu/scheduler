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

            <div class="col s12 m6 required">
                {!! Form::label('Notification[to]', 'Send a notification to') !!}
                {!! Form::text('Notification[to]', $notification['to'], ['required' => true, 'autofocus']) !!}

                @if ($errors->has('Notification.to'))
                    <span class="red-text">{{ $errors->first('Notification.to') }}</span>
                @endif
            </div>

            <div class="col s12 m6">
                {!! Form::hidden('Notification[accept_unsubscribe]', 0) !!}
                {!! Form::checkbox('Notification[accept_unsubscribe]', 1, $notification['accept_unsubscribe'], ['id' => 'Notification[accept_unsubscribe]']) !!}
                {!! Form::label('Notification[accept_unsubscribe]', 'Append unsubscribe link') !!}
            </div>

            <div class="col s12 m6">
                {!! Form::hidden('Notification[with_result]', 0) !!}
                {!! Form::checkbox('Notification[with_result]', 1, $notification['with_result'], ['id' => 'Notification[with_result]']) !!}
                {!! Form::label('Notification[with_result]', 'Append the result when sending this notification') !!}
            </div>
            
            @if (Auth::user()->hasRole('custom-notifications'))
                <div class="col s12 m6">
                    {!! Form::hidden('Notification[only_result]', 0) !!}
                    {!! Form::checkbox('Notification[only_result]', 1, $notification['only_result'], ['id' => 'Notification[only_result]']) !!}
                    {!! Form::label('Notification[only_result]', 'Send only the result when sending this notification') !!}
                </div>

                <div id="subject" class="col s12 m6 {{ $notification['only_result'] || old('Notification.only_result') ? '' : 'hide' }}">
                    {!! Form::label('Notification[subject]', 'Subject') !!}
                    {!! Form::text('Notification[subject]', $notification['subject']) !!}

                    @if ($errors->has('Notification.subject'))
                        <span class="red-text">{{ $errors->first('Notification.subject') }}</span>
                    @endif
                </div>
            @endif

            @if (Auth::user()->hasRole('advanced-notifications'))
                <div class="col s12 m6">
                    {!! Form::hidden('Notification[send_if]', 0) !!}
                    {!! Form::checkbox('Notification[send_if]', 1, $notification['condition'] != null, ['id' => 'Notification[send_if]']) !!}
                    {!! Form::label('Notification[send_if]', 'Send this notification if') !!}
                </div>

                <div id="sendif" class="col s12 m6 {{ $notification['condition'] || old('Notification.condition') ? '' : 'hide' }}">
                    <div class="row">
                        <div class="col s12 m6 required">
                            {!! Form::label('Notification[condition]', 'Condition') !!}
                            {!! Form::select('Notification[condition]', ['eq' => 'Equal', 'ne' => 'Not Equal', 'lt' => 'Less than', 'gt' => 'Great than', 'contains' => 'Contains', 'margin' => 'Margin'], $notification['condition'], ['class' => 'browser-default', 'required' => true]) !!}

                            @if ($errors->has('Notification.status'))
                                <span class="red-text">{{ $errors->first('Notification.status') }}</span>
                            @endif
                        </div>

                        <div class="col s12 m6 required">
                            {!! Form::label('Notification[value]', 'Value') !!}
                            {!! Form::text('Notification[value]', $notification['value'], ['required' => true]) !!}

                            @if ($errors->has('Notification.value'))
                                <span class="red-text">{{ $errors->first('Notification.value') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
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
            <div class="col s12 m6 required">
                {!! Form::label('Slack[username]', 'Username') !!}
                {!! Form::text('Slack[username]', isset($notification['slack']['username']) ? $notification['slack']['username'] : 'deploy') !!}

                @if ($errors->has('Slack.username'))
                    <span class="red-text">{{ $errors->first('Slack.username') }}</span>
                @endif
            </div>

            <div class="col s12 m6 required">
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
        if (this.value == 'slack') {
            $slack.removeClass('hide');
        } else {
            $slack.addClass('hide');
        }
    });

    var $subject = $('#subject');

    $('[name="Notification[only_result]"]').on('change', function() {
        if (this.checked) {
            $subject.removeClass('hide');
        } else {
            $subject.addClass('hide');
        }
    });

    var $sendIf = $('#sendif');

    $('[name="Notification[send_if]"]').on('change', function() {
        if (this.checked) {
            $sendIf.removeClass('hide');
        } else {
            $sendIf.addClass('hide');
        }
    });
});
</script>
@endsection
