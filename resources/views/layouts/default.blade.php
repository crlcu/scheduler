<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scheduler | @yield('page-title')</title>

    @section('styles')
        {!! Html::style('//fonts.googleapis.com/icon?family=Material+Icons') !!}
        {!! Html::style('//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css') !!}
        {!! Html::style('//cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.3/jquery.datetimepicker.min.css') !!}
    @show

    {!! Html::style('css/style.min.css') !!}
</head>
<body>
    <main>
        @include('elements.navbar')
        
        <div id="page" class="container">
            @yield('content')
        </div>

        <div class="push"><!--//--></div>
    </main>

    @include('elements.footer')

    @section('scripts')
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        {!! Html::script('//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js') !!}
        {!! Html::script('//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js') !!}
        {!! Html::script('//cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.3/build/jquery.datetimepicker.full.min.js') !!}

        {{ Html::script('js/app.min.js') }}
    @show
</body>
</html>
