@extends('layouts.default')

@section('content')
    {!! Form::model($role, ['action' => ['RolesController@update', $role['id']], 'method' => 'put', 'novalidate']) !!}
        @include('roles.form')
    {!! Form::close() !!}
@endsection
