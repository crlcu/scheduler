@extends('layouts.default')

@section('content')
    {!! Form::open(['action' => 'RolesController@store', 'novalidate']) !!}
        @include('roles.form')
    {!! Form::close() !!}
@endsection
