@extends('Frontend::layouts.master')

@section('title', __('Space'))

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
    @include('Frontend::services.space.items.slider')
    @include('Frontend::services.space.items.type')
    @include('Frontend::services.space.items.recent')
    @include('Frontend::services.space.items.destination')
    @include('Frontend::services.space.items.testimonial')
    @include('Frontend::components.sections.blog')
@stop

