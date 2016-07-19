@extends('layouts.default')

@section('navbar-items')
    <li>
        <a href="{{ action('TasksController@show', $task['id']) }}" class="waves-effect"><i class="material-icons right">arrow_back</i> {{ $task['name'] }}</a>
    </li>
@endsection

@section('content')
    <div class="widget">
        <div class="header indigo lighten-5">
            <span class="title">Notifications</span>
        </div>
        <div class="content">
            <table class="bordered highlight condensed">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Status</th>
                        <th>To</th>
                        <th width="90px">
                            <a href="{{ action('NotificationsController@create', ['task_id' => $task['id']]) }}" class="btn-floating waves-effect waves-light green right" title="Add">
                                <i class="material-icons">add</i>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($task['notifications']))
                        @foreach ($task['notifications'] as $notification)
                            <tr>
                                <td>{{ $notification['type'] }}</td>
                                <td>{{ $notification['status'] }}</td>
                                <td>
                                    {{ $notification['to'] }}
                                </td>
                                <td>
                                    {!! Form::model($task, ['action' => ['NotificationsController@destroy', $notification['id']], 'method' => 'delete', 'class' => 'delete']) !!}
                                        <button type="submit" class="btn-floating waves-effect waves-light red" title="Remove" onclick="return confirm('Confirm?')">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    {!! Form::close() !!}

                                    <a href="{{ action('NotificationsController@edit', $notification['id']) }}" class="btn-floating waves-effect waves-light blue" title="Edit">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="center-align" colspan="4">
                                <a href="{{ action('NotificationsController@create', ['task_id' => $task['id']]) }}" class="btn waves-effect waves-light green" title="Add">
                                    <i class="material-icons left">add</i> Add your first notification
                                </a>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="footer indigo lighten-5"></div>
    </div>
@endsection
