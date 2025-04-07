<!DOCTYPE html>
<html lang="{{get_current_language()}}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>@yield('title')</title>

        <link rel="stylesheet" href="{{asset('html/assets/vendor/bootstrap-4.0.0/dist/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('html/assets/css/installer.css')}}">
        <link rel="stylesheet" href="{{asset('html/assets/css/main.css')}}">
    </head>
    <body class="body @yield('class_body')">
        <div class="site-content">
            @yield('content')
        </div>

    </body>
</html>
