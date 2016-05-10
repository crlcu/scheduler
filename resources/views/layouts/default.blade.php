<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Title</title>

    @section('styles')
        {!! Html::style('//fonts.googleapis.com/icon?family=Material+Icons') !!}
        {!! Html::style('//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css') !!}
    @show
</head>
<body>
    <div class="page-wrap">
        <div id="page">
            @yield('content')
        </div>

        <div class="push"><!--//--></div>
    </div>

    @section('scripts')
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
    @show
</body>
</html>
