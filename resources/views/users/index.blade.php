@extends('layouts.default')

@section('content')
    <div class="widget">
        <div class="header indigo lighten-5">
            <span class="title">Users</span>
        </div>
        <div class="content">
            <table class="bordered highlight condensed">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Group</th>
                        <th width="90px">
                            <a href="{{ action('UsersController@create') }}" class="btn-floating waves-effect waves-light green right" title="Add">
                                <i class="material-icons">add</i>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ $user['group']['name'] }}</td>
                            <td>
                                {!! Form::model($user, ['action' => ['UsersController@destroy', $user['id']], 'method' => 'delete', 'class' => 'delete']) !!}
                                    <button type="submit" class="btn-floating waves-effect waves-light red" title="Remove" onclick="return confirm('Confirm?')">
                                        <i class="material-icons">delete</i>
                                    </button>
                                {!! Form::close() !!}

                                <a href="{{ action('UsersController@edit', $user['id']) }}" class="btn-floating waves-effect waves-light blue" title="Edit">
                                    <i class="material-icons">edit</i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="footer indigo lighten-5">
            {!! $users->links() !!}
        </div>
    </div>
@endsection
