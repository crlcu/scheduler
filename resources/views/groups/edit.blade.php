@extends('layouts.default')

@section('page-title')
    Groups | {{ $group['name'] }} | Edit
@endsection

@section('content')
    {!! Form::model($group, ['action' => ['GroupsController@update', $group['id']], 'method' => 'put', 'novalidate']) !!}
        @include('groups.form')
    {!! Form::close() !!}
@endsection
