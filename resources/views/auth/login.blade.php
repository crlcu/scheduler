@extends('layouts.guest')

@section('page-title')
    Login
@endsection

@section('content')
    <div class="row">
        {!! Form::open(['action' => 'Auth\LoginController@login', 'class' => 'col offset-s1 s10 offset-m2 m8 offset-l4 l4', 'novalidate']) !!}
            <div class="card-panel z-depth-1">
                {!! csrf_field() !!}

                <p class="center-align">{!! Html::image('images/logo_64x64.png', 'Tasks scheduler login') !!}</p>

                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">person</i>
                        {!! Form::label('email', 'Email') !!}
                        {!! Form::text('email', null, ['required' => true, 'autofocus']) !!}

                        @if ($errors->has('email'))
                            <span class="red-text">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <div class="input-field col s12">
                        <i class="material-icons prefix">lock_outline</i>
                        {!! Form::label('password', 'Password') !!}
                        {!! Form::password('password', ['required' => true]) !!}

                        @if ($errors->has('password'))
                            <span class="red-text">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    <div class="input-field col s12 hide">
                        <p>
                            {!! Form::checkbox('remember', true, true, ['id' => 'remember']) !!}
                            {!! Form::label('remember', 'Remember me') !!}
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <button type="submit" class="btn waves-effect waves-light green col s12">Login</button>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('scripts')
@parent
<script type="text/javascript">
$(document).ready(function() {
    var $wrapper = $('form');

    repositioning($wrapper);
    
    $( window ).resize(function() {
        repositioning($wrapper);
    });
});

function repositioning( $element ) {
    var screenHeight = $('main').innerHeight();
    var elementHeight = $element.innerHeight();
    
    if ( (screenHeight - elementHeight) / 2 > 0 ) {
        $element.animate({'margin-top': (screenHeight - elementHeight) / 2}, 1);
    }
}
</script>
@stop
