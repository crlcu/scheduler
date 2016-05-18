@extends('layouts.default')

@section('content')
    {!! Form::open(['action' => 'GroupsController@store', 'novalidate']) !!}
        @include('groups.form')
    {!! Form::close() !!}
@endsection
