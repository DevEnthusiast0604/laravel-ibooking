<!DOCTYPE html>
<html lang="{{get_current_language()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $favicon = get_favicon();
    @endphp
    @if($favicon)
        <link rel="shortcut icon" type="image/png" href="{{ $favicon }}"/>
    @endif

    <title>{{get_translate(get_option('site_name', 'iBooking'))}} {{get_seo_title_separator()}} @yield('title')</title>

    @php
        admin_enqueue_styles('gmz-form2');
        admin_enqueue_scripts('gmz-form2');
    @endphp

    @php admin_init_header(); @endphp
</head>
<body class="form {{rtl_class()}}">

<div class="form-container outer">
    <div class="form-form">
        <div class="form-form-wrap">
            <div class="form-container">
                <div class="form-content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>

@php admin_init_footer(); @endphp

</body>
</html>