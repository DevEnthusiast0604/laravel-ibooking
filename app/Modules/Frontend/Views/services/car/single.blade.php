@extends('Frontend::layouts.master')
@section('title', get_translate($post['post_title']))
@section('class_body', 'single-car single-service')

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
    $features = $post['car_feature'];
    $equipments = $post['car_equipment'];
    $equipments_custom = maybe_unserialize($post['equipments']);
    $enable_cancellation = $post['enable_cancellation'];
    $cancel_before = $post['cancel_before'];
    $cancellation_detail = $post['cancellation_detail'];


@endphp

@section('content')
    @include('Frontend::services.car.single.gallery')
    @php
        the_breadcrumb($post, 'car');
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-lg-8 pb-5">
                <h1 class="post-title">
                    @php echo add_wishlist_box($post['id'], GMZ_SERVICE_CAR); @endphp
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
                            <span class="value">{{$post['passenger']}}</span>
                            <span class="label">{{__('Passenger')}}</span>
                        </li>
                        <li>
                            <span class="value">{{get_gear_shift($post['gear_shift'])}}</span>
                            <span class="label">{{__('Gear shift')}}</span>
                        </li>
                        <li>
                            <span class="value">{{$post['baggage']}}</span>
                            <span class="label">{{__('Baggage')}}</span>
                        </li>
                        <li>
                            <span class="value">{{$post['door']}}</span>
                            <span class="label">{{__('Door')}}</span>
                        </li>
                        @php
                            $term = get_term('id', $post['car_type']);
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
                @if(!empty($features))
                    <section class="feature">
                        <h2 class="section-title">{{__('Features')}}</h2>
                        <div class="section-content">
                            @php
                                $features = explode(',', $features);
                            @endphp
                            <div class="row">
                                @foreach($features as $item)
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
                @if(!empty($equipments))
                    <section class="equipment">
                        <h2 class="section-title">{{__('Equipments')}}</h2>
                        <div class="section-content">
                            @php
                                $equipments = explode(',', $equipments);
                            @endphp
                            <div class="row">
                                @foreach($equipments as $item)
                                    @php
                                        $term = get_term('id', $item);
                                    @endphp
                                    @if($term)
                                        @php
                                        if($equipments_custom[$item]['price']){
                                            $equip_price = $equipments_custom[$item]['price'];
                                        }else{
                                            $equip_price = $term->term_price;
                                        }
                                        @endphp
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
                                                    {{get_translate($term->term_title)}} <small>({{convert_price($equip_price) }})</small>
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
                                {{sprintf(__('Customers can cancel this Car before %s day(s)'), $cancel_before)}}
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
                    <h2 class="section-title">{{__('Car On Map')}}</h2>
                    <div class="section-content">
                        <div class="map-single" data-lat="{{$post['location_lat']}}" data-lng="{{$post['location_lng']}}"></div>
                    </div>
                </section>
                @include('Frontend::services.car.single.review')
            </div>
            <div class="col-lg-4">
                <div class="siderbar-single">
                @include('Frontend::services.car.single.booking-form')
                    <div id="booking-mobile" class="booking-mobile btn btn-primary">
                        {{__('Check Availability')}}
                    </div>
                    @include('Frontend::components.sections.partner-info')
                </div>
            </div>
        </div>
    </div>
    @include('Frontend::services.car.single.nearby')
@stop

