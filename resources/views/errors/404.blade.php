@extends('layouts.default')

@section('content')
    <div class="container">
        <div class="center-align">
            <h3>OUPS<br/><small>Something just went wrong!</small></h3>
            
            <a href="{{ url('/') }}" class="btn waves-effect waves-light green" title="Home">
                <i class="material-icons left">home</i> Back to home
            </a>
        </div>

        <div class="hide">
            {{ $exception->getMessage() }}
        </div>
    </div>
@endsection
