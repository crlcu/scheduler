@extends('layouts.default')

@section('page-title')
    Users | Add
@endsection

@section('content')
    {!! Form::open(['action' => 'UsersController@store', 'novalidate']) !!}
        @include('users.form')
    {!! Form::close() !!}
@endsection
