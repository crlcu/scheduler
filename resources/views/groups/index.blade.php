@extends('layouts.default')

@section('content')
    <table class="bordered highlight condensed">
        <thead>
            <tr>
                <th>Name</th>
                <th>Users in this group</th>
                <th width="90px">
                    <a href="{{ action('GroupsController@create') }}" class="btn-floating waves-effect waves-light green right" title="Add">
                        <i class="material-icons">add</i>
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groups as $group)
                <tr>
                    <td>{{ $group['name'] }}</td>
                    <td>{{ count($group['users']) }}</td>
                    <td>
                        {!! Form::model($group, ['action' => ['GroupsController@destroy', $group['id']], 'method' => 'delete', 'class' => 'delete']) !!}
                            <button type="submit" class="btn-floating waves-effect waves-light red" title="Remove" onclick="return confirm('Confirm?')">
                                <i class="material-icons">delete</i>
                            </button>
                        {!! Form::close() !!}

                        <a href="{{ action('GroupsController@edit', $group['id']) }}" class="btn-floating waves-effect waves-light blue" title="Edit">
                            <i class="material-icons">edit</i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
