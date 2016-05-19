<div class="widget">
    <div class="header indigo lighten-5">
        <span class="title">User details</span>
    </div>
    <div class="content">
        <div class="row">
            <div class="input-field col s12">
                {!! Form::label('User[name]', 'Name') !!}
                {!! Form::text('User[name]', $user['name'], ['required' => true, 'autofocus']) !!}

                @if ($errors->has('User.name'))
                    <span class="red-text">{{ $errors->first('User.name') }}</span>
                @endif
            </div>

            <div class="input-field col s12">
                {!! Form::label('User[email]', 'Email') !!}
                {!! Form::text('User[email]', $user['email'], ['required' => true]) !!}

                @if ($errors->has('User.email'))
                    <span class="red-text">{{ $errors->first('User.email') }}</span>
                @endif
            </div>

            <div class="col s12">
                {!! Form::label('User[group_id]', 'Group') !!}
                {!! Form::select('User[group_id]', $groups->lists('name', 'id'), $user['group_id'], ['class' => 'browser-default', 'required' => true]) !!}

                @if ($errors->has('User.email'))
                    <span class="red-text">{{ $errors->first('User.email') }}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="footer indigo lighten-5">
        <div class="row">
            <div class="col s12">
                <button type="submit" class="btn waves-effect waves-light green right">
                    <i class="material-icons left">done</i> Save
                </button>
            </div>
        </div>
    </div>
</div>
