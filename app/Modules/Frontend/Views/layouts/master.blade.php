<!DOCTYPE html>
<html lang="{{get_current_language()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $favicon = get_favicon();
        if($favicon)
            echo '<link rel="shortcut icon" type="image/png" href="'. $favicon .'"/>';
    @endphp


    @php
        $page_title = seo_page_title();
        if($page_title){
            $title_tag =  $page_title;
        }else{
            $site_name = get_translate(get_option('site_name', 'iBooking'));
            $seo_separator_title = get_seo_title_separator();
            $title_tag = $site_name . ' ' . $seo_separator_title;
        }
    @endphp<title>@php echo $title_tag @endphp @yield('title')</title>

    {!! seo_meta(); !!}
    @php init_header(); @endphp
</head>
<body class="body @yield('class_body') {{rtl_class()}}">
@include('Frontend::components.admin-bar')
@include('Frontend::components.top-bar-1')
@include('Frontend::components.header')
<div class="site-content">
    @yield('content')
</div>
@include('Frontend::components.footer')
@php init_footer(); @endphp
</body>
</html>
