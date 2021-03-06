<div class="widget">
    <div class="header indigo lighten-5">
        <span class="title">Group details</span>
    </div>
    <div class="content">
        <div class="row">
            <div class="input-field col s12">
                {!! Form::label('Group[name]', 'Name') !!}
                {!! Form::text('Group[name]', $group['name'], ['required' => true, 'autofocus']) !!}

                @if ($errors->has('Group.name'))
                    <span class="red-text">{{ $errors->first('Group.name') }}</span>
                @endif
            </div>

            <div class="col s12">
                @foreach ($roles as $role)
                    {!! Form::checkbox('Role[]', $role['id'], count($group->roles->where('name', $role['name'])), ['id' => 'Role['. $role['name'] . ']']) !!}
                    {!! Form::label('Role['. $role['name'] . ']', $role['description']) !!}
                @endforeach
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
