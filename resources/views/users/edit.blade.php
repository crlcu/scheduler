@extends('layouts.default')

@section('content')
    {!! Form::model($user, ['action' => ['UsersController@update', $user['id']], 'method' => 'put', 'novalidate']) !!}
        @include('users.form')
    {!! Form::close() !!}
@endsection
