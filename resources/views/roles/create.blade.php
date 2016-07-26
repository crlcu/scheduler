@extends('layouts.default')

@section('page-title')
    Roles | Add
@endsection

@section('content')
    {!! Form::open(['action' => 'RolesController@store', 'novalidate']) !!}
        @include('roles.form')
    {!! Form::close() !!}
@endsection
