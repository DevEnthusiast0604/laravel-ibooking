@extends('Frontend::layouts.master')

@section('title', __('Car'))

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
    @include('Frontend::services.car.items.slider')
    @include('Frontend::services.car.items.type')
    @include('Frontend::services.car.items.recent')
    @include('Frontend::services.car.items.destination')
    @include('Frontend::services.car.items.testimonial')
    @include('Frontend::components.sections.blog')
@stop

