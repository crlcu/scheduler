@extends('layouts.default')

@section('content')
    {!! Form::open(['action' => 'UsersController@store', 'novalidate']) !!}
        @include('users.form')
    {!! Form::close() !!}
@endsection
