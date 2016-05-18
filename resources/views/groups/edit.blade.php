@extends('layouts.default')

@section('content')
    {!! Form::model($group, ['action' => ['GroupsController@update', $group['id']], 'method' => 'put', 'novalidate']) !!}
        @include('groups.form')
    {!! Form::close() !!}
@endsection
