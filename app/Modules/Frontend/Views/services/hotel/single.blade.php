@extends('Frontend::layouts.master')
@section('title', get_translate($post['post_title']))
@section('class_body', 'single-hotel single-service')

@php
    enqueue_styles([
       'mapbox-gl',
       'mapbox-gl-geocoder',
       'daterangepicker',
    ]);
    enqueue_scripts([
       'mapbox-gl',
       'mapbox-gl-geocoder',
       'moment',
       'daterangepicker'
    ]);
    $post_content = get_translate($post['post_content']);
    $enable_cancellation = $post['enable_cancellation'];
    $cancel_before = $post['cancel_before'];
    $cancellation_detail = $post['cancellation_detail'];
@endphp

@section('content')
    @include('Frontend::services.hotel.single.gallery')
    @php
        the_breadcrumb($post, GMZ_SERVICE_HOTEL);
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-lg-8 pb-5">
                @include('Frontend::services.hotel.single.header')

                @include('Frontend::services.hotel.single.meta')

                @if(!empty($post_content))
                    <section class="description">
                        <h2 class="section-title">{{__('Detail')}}</h2>
                        <div class="section-content">
                            {!! balance_tags($post_content) !!}
                        </div>
                    </section>
                @endif

                @include('Frontend::services.hotel.single.availability')

                <section class="map">
                    <h2 class="section-title">{{__('Hotel On Map')}}</h2>
                    <div class="section-content">
                        <div class="map-single" data-lat="{{$post['location_lat']}}" data-lng="{{$post['location_lng']}}"></div>
                    </div>
                </section>

                @include('Frontend::services.hotel.single.policy')

                @include('Frontend::services.hotel.single.faq')

                @include('Frontend::services.hotel.single.review')
            </div>
            <div class="col-lg-4">
                <div class="siderbar-single">
                    @php
                        $hotel_logo = $post['hotel_logo'];
                        $facilities = $post['hotel_facilities'];
                        $hotel_services = $post['hotel_services'];
                    @endphp
                    @if(!empty($hotel_logo))
                        <div class="hotel-logo">
                            @php
                                $hotel_logo_url = get_attachment_url($hotel_logo);
                            @endphp
                            <img src="{{$hotel_logo_url}}" class="img-fluid" alt="hotel logo"/>
                        </div>
                    @endif
                    @if(!empty($facilities))
                        <section class="feature">
                            <h2 class="section-title">{{__('Facilities')}}</h2>
                            <div class="section-content">
                                @php
                                    $facilities = explode(',', $facilities);
                                @endphp
                                @foreach($facilities as $item)
                                    @php
                                        $term = get_term('id', $item);
                                    @endphp
                                    @if($term)
                                        <div class="term-item">
                                            @if(!empty($term->term_icon))
                                                @if(strpos($term->term_icon, ' fa-'))
                                                    <i class="{{$term->term_icon}} term-icon"></i>
                                                @else
                                                    {!! get_icon($term->term_icon) !!}
                                                @endif
                                            @endif
                                            <div class="term-item__title">{{get_translate($term->term_title)}}</div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if(!empty($hotel_services))
                        <section class="feature">
                            <h2 class="section-title">{{__('Hotel Services')}}</h2>
                            <div class="section-content">
                                @php
                                    $hotel_services = explode(',', $hotel_services);
                                @endphp
                                @foreach($hotel_services as $item)
                                    @php
                                        $term = get_term('id', $item);
                                    @endphp
                                    @if($term)
                                        <div class="term-item">
                                            @if(!empty($term->term_icon))
                                                @if(strpos($term->term_icon, ' fa-'))
                                                    <i class="{{$term->term_icon}} term-icon"></i>
                                                @else
                                                    {!! get_icon($term->term_icon) !!}
                                                @endif
                                            @endif
                                            <div class="term-item__title">{{get_translate($term->term_title)}}</div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @include('Frontend::services.hotel.single.nearby-location')

                    @include('Frontend::components.sections.partner-info')

                </div>
            </div>
        </div>
    </div>
    @include('Frontend::services.hotel.single.nearby')
@stop

