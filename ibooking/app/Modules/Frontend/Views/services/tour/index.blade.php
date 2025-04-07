@extends('Frontend::layouts.master')

@section('title', __('Tour'))

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
    @include('Frontend::services.tour.items.slider')
    @include('Frontend::services.tour.items.type')
    @include('Frontend::services.tour.items.recent')
    @include('Frontend::services.tour.items.destination')
    @include('Frontend::services.tour.items.testimonial')
    @include('Frontend::components.sections.blog')
@stop

