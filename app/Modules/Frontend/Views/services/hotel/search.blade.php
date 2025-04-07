@extends('Frontend::layouts.master')

@section('title', __('Hotel Search Page'))
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
        'gmz-search-hotel'
    ]);
@endphp

@section('content')
    <section class="search-archive-top bg-secondary">
        <div class="container">
            <div class="search-form-wrapper">
                <div class="hotel-search-form">
                    @php
                        $text_slider = get_translate(get_option('hotel_slider_text'));
                    @endphp
                    @if(!empty($text_slider))
                        <p class="_title">{{$text_slider}}</p>
                    @endif
                    @include('Frontend::services.hotel.search-form', ['advanced' => false])
                </div>
            </div>
        </div>
    </section>
    <section class="list-half-map gmz-search-result" data-action="{{url('hotel-search')}}">
        <div class="container-fluid">
            <div class="search-filter d-flex align-items-center">
                <div class="heading"><i class="fal fa-sliders-v-square"></i></div>
                @include('Frontend::services.hotel.filter.price')
                @include('Frontend::services.hotel.filter.star')
                @include('Frontend::services.hotel.filter.term')
            </div>
            <div class="row">
                @include('Frontend::services.hotel.search.result')
                @include('Frontend::services.hotel.search.map')
            </div>
        </div>
    </section>
@stop

