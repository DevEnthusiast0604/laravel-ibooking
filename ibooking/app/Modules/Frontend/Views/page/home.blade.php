@extends('Frontend::layouts.master')

@section('title', __('Home Page'))

@php
    enqueue_styles([
        'slick',
        'daterangepicker'
    ]);
    enqueue_scripts([
        'slick',
        'moment',
        'daterangepicker'
    ]);
@endphp
@section('content')
    @include('Frontend::page.home.slider')
    @action('gmz_homepage_after_slider')
    @include('Frontend::services.hotel.items.type')
    @include('Frontend::services.hotel.items.recent')
    @include('Frontend::services.car.items.type')
    @include('Frontend::services.car.items.recent')
    @include('Frontend::services.apartment.items.type')
    @include('Frontend::services.apartment.items.recent')
    @include('Frontend::services.tour.items.type')
    @include('Frontend::services.tour.items.recent')
    @include('Frontend::services.space.items.type')
    @include('Frontend::services.space.items.recent')
    @include('Frontend::services.beauty.items.type')
    @include('Frontend::services.beauty.items.recent')
    @include('Frontend::page.home.destination')
    @include('Frontend::page.home.testimonial')
    @include('Frontend::components.sections.blog')
@stop

