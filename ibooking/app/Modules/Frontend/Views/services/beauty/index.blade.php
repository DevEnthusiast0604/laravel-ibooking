@extends('Frontend::layouts.master')

@section('title', __('Beauty Services'))

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
    @include('Frontend::services.beauty.items.slider')
    @include('Frontend::services.beauty.items.type')
    @include('Frontend::services.beauty.items.recent')
    @include('Frontend::services.beauty.items.destination')
    @include('Frontend::services.beauty.items.testimonial')
    @include('Frontend::components.sections.blog')
@stop

