<!DOCTYPE html>
<html lang="{{get_current_language()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $favicon = get_favicon();
    @endphp
    @if($favicon)
        <link rel="shortcut icon" type="image/png" href="{{ $favicon }}"/>
    @endif

    <title>{{get_translate(get_option('site_name', 'iBooking'))}} - @yield('title')</title>

    @php admin_init_header(); @endphp
    {{--@stack('styles')--}}

</head>
<body class="gmz-body sidebar-noneoverflow {{rtl_class()}} {{body_class()}}">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    @include('Backend::components.header')
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        @include('Backend::components.sidebar')
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">

            <div class="layout-px-spacing">
                @yield('content')
            </div>

            <!-- BEGIN FOOTER -->
            @include('Backend::components.footer')
            <!-- END FOOTER -->
        </div>
        <!--  END CONTENT AREA  -->


    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    @php admin_init_footer(); @endphp
    {{--@stack('scripts')--}}

    <script>
        $(document).ready(function() {
            App.init();
        });
        feather.replace();
    </script>
    </body>
</html>
