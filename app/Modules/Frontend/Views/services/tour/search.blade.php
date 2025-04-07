@extends('Frontend::layouts.master')

@section('title', __('Tour Search Page'))
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
        'gmz-search-tour'
    ]);
@endphp

@section('content')
    <section class="search-archive-top bg-secondary">
        <div class="container">
            <div class="search-form-wrapper">
                <div class="tour-search-form">
                    @php
                        $text_slider = get_translate(get_option('tour_slider_text'));
                    @endphp
                    @if(!empty($text_slider))
                        <p class="_title">{{$text_slider}}</p>
                    @endif
                    @include('Frontend::services.tour.search-form', ['advanced' => false])
                </div>
            </div>
        </div>
    </section>
    <section class="list-half-map gmz-search-result" data-action="{{url('tour-search')}}">
        <div class="container-fluid">
            <div class="search-filter d-flex align-items-center">
                <div class="heading"><i class="fal fa-sliders-v-square"></i></div>
                @include('Frontend::services.tour.filter.price')
                @include('Frontend::services.tour.filter.term')
            </div>
            <div class="row">
                @include('Frontend::services.tour.search.result')
                @include('Frontend::services.tour.search.map')
            </div>
        </div>
    </section>
@stop

