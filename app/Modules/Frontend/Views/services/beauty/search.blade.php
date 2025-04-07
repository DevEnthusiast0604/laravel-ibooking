@extends('Frontend::layouts.master')

@section('title', __('Beauty Services Search Page'))
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
        'gmz-search-beauty'
    ]);
@endphp

@section('content')
    <section class="search-archive-top bg-secondary">
        <div class="container">
            <div class="search-form-wrapper">
                <div class="beauty-search-form">
                    @php
                        $text_slider = get_translate(get_option('space_slider_text'));
                    @endphp
                    @if(!empty($text_slider))
                        <p class="_title">{{$text_slider}}</p>
                    @endif
                    @include('Frontend::services.beauty.search-form', ['advanced' => false])
                </div>
            </div>
        </div>
    </section>
    <section class="list-half-map gmz-search-result" data-action="{{url('beauty-search')}}">
        <div class="container-fluid">
            <div class="row">
                @include('Frontend::services.beauty.search.result')
                @include('Frontend::services.beauty.search.map')
            </div>
        </div>
    </section>
@stop

