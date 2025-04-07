@extends('Frontend::layouts.master')
@section('title', get_translate($post['post_title']))
@section('class_body', 'single-space single-service')

@php
    enqueue_styles([
       'mapbox-gl',
       'mapbox-gl-geocoder',
       'daterangepicker'
    ]);
    enqueue_scripts([
       'mapbox-gl',
       'mapbox-gl-geocoder',
       'moment',
       'daterangepicker'
    ]);
    $post_content = get_translate($post['post_content']);
    $amenities = $post['space_amenity'];
    $enable_cancellation = $post['enable_cancellation'];
    $cancel_before = $post['cancel_before'];
    $cancellation_detail = $post['cancellation_detail'];
@endphp

@section('content')
    @include('Frontend::services.space.single.gallery')
    @php
        the_breadcrumb($post, GMZ_SERVICE_SPACE);
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-lg-8 pb-5">
                <h1 class="post-title">
                    @php echo add_wishlist_box($post['id'], GMZ_SERVICE_SPACE); @endphp
                    {{get_translate($post['post_title'])}}
                    @if($post['is_featured'] == 'on')
                        <span class="is-featured">{{__('Featured')}}</span>
                    @endif
                </h1>
                @if(!empty($post['rating']))
                    <div class="count-reviews">
                        @php
                            review_rating_star($post['rating'])
                        @endphp
                    </div>
                @endif
                <p class="location">
                    <i class="fal fa-map-marker-alt"></i> {{get_translate($post['location_address'])}}
                </p>
                <div class="meta">
                    <ul>
                        <li>
                            <span class="value">{{$post['number_of_guest']}}</span>
                            <span class="label">{{__('Guests')}}</span>
                        </li>
                        <li>
                            <span class="value">{{get_translate($post['number_of_bedroom'])}}</span>
                            <span class="label">{{__('Bedrooms')}}</span>
                        </li>
                        <li>
                            <span class="value">{{$post['number_of_bathroom']}}</span>
                            <span class="label">{{__('Bathrooms')}}</span>
                        </li>
                        <li>
                            <span class="value">{{$post['size']}}</span>
                            <span class="label">{{__('Size')}}<small> ({{get_option('unit_of_measure', 'm2')}})</small></span>
                        </li>
                        @php
                            $term = get_term('id', $post['space_type']);
                        @endphp
                        @if($term)
                            <li>
                                <span class="value">{{get_translate($term->term_title)}}</span>
                                <span class="label">{{__('Type')}}</span>
                            </li>
                        @endif
                    </ul>
                </div>
                @if(!empty($post_content))
                    <section class="description">
                        <h2 class="section-title">{{__('Detail')}}</h2>
                        <div class="section-content">
                            {!! balance_tags($post_content) !!}
                        </div>
                    </section>
                @endif
                @if(!empty($amenities))
                    <section class="feature">
                        <h2 class="section-title">{{__('Amenities')}}</h2>
                        <div class="section-content">
                            @php
                                $amenities = explode(',', $amenities);
                            @endphp
                            <div class="row">
                                @foreach($amenities as $item)
                                    @php
                                        $term = get_term('id', $item);
                                    @endphp
                                    @if($term)
                                        <div class="col-md-3 col-6">
                                            <div class="term-item">
                                                @if(!empty($term->term_icon))
                                                    @if(strpos($term->term_icon, ' fa-'))
                                                        <i class="{{$term->term_icon}} term-icon"></i>
                                                    @else
                                                        {!! get_icon($term->term_icon) !!}
                                                    @endif
                                                @endif
                                                <div class="term-item__title">
                                                    {{get_translate($term->term_title)}}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
                @if($enable_cancellation == 'on')
                    <section class="policy">
                        <h2 class="section-title">{{__('Policies')}}</h2>
                        <div class="section-content">
                            <div class="cancel-day">
                                {{sprintf(__('Customers can cancel this Space before %s day(s)'), $cancel_before)}}
                            </div>
                            @if(!empty($cancellation_detail))
                                <div class="cancel-detail">
                                    {{get_translate($cancellation_detail)}}
                                </div>
                            @endif
                        </div>
                    </section>
                @endif
                <section class="map">
                    <h2 class="section-title">{{__('Space On Map')}}</h2>
                    <div class="section-content">
                        <div class="map-single" data-lat="{{$post['location_lat']}}" data-lng="{{$post['location_lng']}}"></div>
                    </div>
                </section>
                @include('Frontend::services.space.single.review')
            </div>
            <div class="col-lg-4">
                <div class="siderbar-single">
                    @include('Frontend::services.space.single.booking-form')
                    <div id="booking-mobile" class="booking-mobile btn btn-primary">
                        {{__('Check Availability')}}
                    </div>
                    @include('Frontend::components.sections.partner-info')
                </div>
            </div>
        </div>
    </div>
    @include('Frontend::services.space.single.nearby')
@stop

