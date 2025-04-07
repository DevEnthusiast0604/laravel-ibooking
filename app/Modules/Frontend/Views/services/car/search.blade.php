@extends('Frontend::layouts.master')

@section('title', __('Car Search Page'))
@section('class_body', 'search-page')

@php
    enqueue_styles([
        'slick',
        'daterangepicker'
    ]);
    enqueue_scripts([
        'slick',
        'moment',
        'daterangepicker',
        'jquery.nicescroll',
        'match-height',
        'gmz-search-car'
    ]);
@endphp

@section('content')
    <section class="search-archive-top bg-secondary">
        <div class="container">
            <div class="search-form-wrapper">
                <div class="car-search-form">
                    @php
                        $text_slider = get_translate(get_option('car_slider_text'));
                    @endphp
                    @if(!empty($text_slider))
                        <p class="_title">{{$text_slider}}</p>
                    @endif
                    @include('Frontend::services.car.search-form', ['advanced' => false])
                </div>
            </div>
        </div>
    </section>
    <section class="list-half-map gmz-search-result" data-action="{{url('car-search')}}">
        <div class="container-fluid">
            <div class="search-filter d-flex align-items-center">
                <div class="heading"><i class="fal fa-sliders-v-square"></i></div>
                @include('Frontend::services.car.filter.price')
                @include('Frontend::services.car.filter.term')
            </div>
            <div class="row">
                @include('Frontend::services.car.search.result')
                @include('Frontend::services.car.search.map')
            </div>
        </div>
    </section>
@stop

