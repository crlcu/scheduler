@extends('layouts.default')

@section('content')
    {!! Form::model($notification, ['action' => ['NotificationsController@update', $notification['id']], 'method' => 'put', 'novalidate']) !!}
        @include('notifications.form')
    {!! Form::close() !!}
@endsection
