@extends('Frontend::layouts.master')

@section('title', __('Hotel'))

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
    @include('Frontend::services.hotel.items.slider')
    @include('Frontend::services.hotel.items.type')
    @include('Frontend::services.hotel.items.recent')
    @include('Frontend::services.hotel.items.destination')
    @include('Frontend::services.hotel.items.testimonial')
    @include('Frontend::components.sections.blog')
@stop

