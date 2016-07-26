@extends('layouts.default')

@section('page-title')
    Users | {{ $user['name'] }} | Edit
@endsection

@section('content')
    {!! Form::model($user, ['action' => ['UsersController@update', $user['id']], 'method' => 'put', 'novalidate']) !!}
        @include('users.form')
    {!! Form::close() !!}
@endsection
