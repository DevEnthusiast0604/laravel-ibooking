@extends('Frontend::layouts.master')

@section('title', __('Apartment'))

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
    @include('Frontend::services.apartment.items.slider')
    @include('Frontend::services.apartment.items.type')
    @include('Frontend::services.apartment.items.recent')
    @include('Frontend::services.apartment.items.destination')
    @include('Frontend::services.apartment.items.testimonial')
    @include('Frontend::components.sections.blog')
@stop

