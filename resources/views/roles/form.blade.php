<div class="widget">
    <div class="header indigo lighten-5">
        <span class="title">Role Details</span>
    </div>
    <div class="content">
        <div class="row">
            <div class="input-field col s12">
                {!! Form::label('Role[name]', 'Name') !!}
                {!! Form::text('Role[name]', $role['name'], ['required' => true, 'autofocus']) !!}

                @if ($errors->has('Role.name'))
                    <span class="red-text">{{ $errors->first('Role.name') }}</span>
                @endif
            </div>

            <div class="input-field col s12">
                {!! Form::label('Role[description]', 'Description') !!}
                {!! Form::text('Role[description]', $role['description'], ['required' => true]) !!}

                @if ($errors->has('Role.description'))
                    <span class="red-text">{{ $errors->first('Role.description') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col s12">
        <button type="submit" class="btn waves-effect waves-light green right">
            <i class="material-icons left">done</i> Save
        </button>
    </div>
</div>
