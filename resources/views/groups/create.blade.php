@extends('layouts.default')

@section('page-title')
    Groups | Add
@endsection

@section('content')
    {!! Form::open(['action' => 'GroupsController@store', 'novalidate']) !!}
        @include('groups.form')
    {!! Form::close() !!}
@endsection
