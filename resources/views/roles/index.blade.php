@extends('layouts.default')

@section('content')
    <table class="bordered highlight condensed">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Groups with this role</th>
                <th width="90px">
                    <a href="{{ action('RolesController@create') }}" class="btn-floating waves-effect waves-light green right" title="Add">
                        <i class="material-icons">add</i>
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role['name'] }}</td>
                    <td>{{ $role['description'] }}</td>
                    <td>{{ count($role['groups']) }}</td>
                    <td>
                        {!! Form::model($role, ['action' => ['RolesController@destroy', $role['id']], 'method' => 'delete', 'class' => 'delete']) !!}
                            <button type="submit" class="btn-floating waves-effect waves-light red" title="Remove" onclick="return confirm('Confirm?')">
                                <i class="material-icons">delete</i>
                            </button>
                        {!! Form::close() !!}

                        <a href="{{ action('RolesController@edit', $role['id']) }}" class="btn-floating waves-effect waves-light blue" title="Edit">
                            <i class="material-icons">edit</i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
