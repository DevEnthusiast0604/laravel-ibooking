@extends('Frontend::layouts.master')
@section('title', get_translate($post['post_title']))
@section('class_body', 'single-tour single-service')

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
    $highlight = maybe_unserialize($post['highlight']);
    $enable_cancellation = $post['enable_cancellation'];
    $cancel_before = $post['cancel_before'];
    $cancellation_detail = $post['cancellation_detail'];

@endphp

@section('content')
    @include('Frontend::services.tour.single.gallery')
    @php
        the_breadcrumb($post, GMZ_SERVICE_TOUR);
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-lg-8 pb-5">
                <h1 class="post-title">
                    @php echo add_wishlist_box($post['id'], GMZ_SERVICE_TOUR); @endphp
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
                        @php
                            $term = get_term('id', $post['tour_type']);
                        @endphp
                        @if($term)
                            <li>
                                <i class="fal fa-tags"></i>
                                <div>
                                    <span class="label">{{__('Type')}}</span>
                                    <span class="value">{{get_translate($term->term_title)}}</span>
                                </div>
                            </li>
                        @endif
                        <li>
                            <i class="fal fa-calendar-alt"></i>
                            <div>
                                <span class="label">{{__('Duration')}}</span>
                                <span class="value">{{get_translate($post['duration'])}}</span>
                            </div>
                        </li>
                        <li>
                            <i class="fal fa-users"></i>
                            <div>
                                <span class="label">{{__('Group Size')}}</span>
                                <span class="value">{{sprintf(__('%s people'), $post['group_size'])}}</span>
                            </div>
                        </li>
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
                @if(!empty($highlight))
                    <section class="highlight">
                        <h2 class="section-title">{{__('Highlight')}}</h2>
                        <div class="section-content">
                            <ul>
                            @foreach($highlight as $k => $v)
                                <li>{{get_translate($v['content'])}}</li>
                            @endforeach
                            </ul>
                        </div>
                    </section>
                @endif

                @include('Frontend::services.tour.single.itinerary')

                @include('Frontend::services.tour.single.inexclude')


                @if($enable_cancellation == 'on')
                    <section class="policy">
                        <h2 class="section-title">{{__('Policies')}}</h2>
                        <div class="section-content">
                            <div class="cancel-day">
                                {{sprintf(__('Customers can cancel this Tour before %s day(s)'), $cancel_before)}}
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
                    <h2 class="section-title">{{__('Tour On Map')}}</h2>
                    <div class="section-content">
                        <div class="map-single" data-lat="{{$post['location_lat']}}" data-lng="{{$post['location_lng']}}"></div>
                    </div>
                </section>
                @include('Frontend::services.tour.single.faq')
                @include('Frontend::services.tour.single.review')
            </div>
            <div class="col-lg-4">
                <div class="siderbar-single">
                    @include('Frontend::services.tour.single.booking-form')
                    <div id="booking-mobile" class="booking-mobile btn btn-primary">
                        {{__('Check Availability')}}
                    </div>
                    @include('Frontend::components.sections.partner-info')
                </div>
            </div>
        </div>
    </div>
    @include('Frontend::services.tour.single.nearby')
@stop

