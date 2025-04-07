@extends('Frontend::layouts.master')
@section('title', get_translate($post['post_title']))
@section('class_body', 'single-beauty single-service')
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
       'daterangepicker',
    ]);
    $post_content = get_translate($post['post_content']);
    $term_service = get_term('id', $post['service']);
    $term_branch = get_term('id', $post['branch']);
    $today = strtotime('today');
    $service_start = date(get_time_format(),$today + $post['service_starts']);
    $service_end = date(get_time_format(),$today + $post['service_ends']);
    $service_duration = round($post['service_duration'] / 60);
    $enable_cancellation = $post['enable_cancellation'];
    $cancel_before = $post['cancel_before'];
    $cancellation_detail = $post['cancellation_detail'];
@endphp

@section('content')
    @include('Frontend::services.space.single.gallery')
    @php
        the_breadcrumb($post, GMZ_SERVICE_BEAUTY);
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-lg-8 pb-5">
                <h1 class="post-title">
                    @php echo add_wishlist_box($post['id'], GMZ_SERVICE_BEAUTY); @endphp
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
                @if($term_branch)
                    <span class="branch">
                        <i class="far fa-store"></i>
                        <a href="javascript:void(0)">
                            {{get_translate($term_branch->term_title)}}
                        </a>
                    </span>
                @endif
                @if($term_service)
                    <span class="service-name">
                        <i class="far fa-spa"></i>
                        <a href="javascript:void(0)">
                           {{get_translate($term_service->term_title)}}
                        </a>
                    </span>
                @endif
                @if(!empty($post['location_address']))
                <p class="location">
                    <i class="far fa-map-marker-alt"></i>
                    <a href="javascript:void(0)">
                        {{get_translate($post['location_address'])}}
                    </a>
                </p>
                @endif

                <div class="meta">
                    <ul>
                        <li>
                            <span class="value">{{$service_start}}</span>
                            <span class="label">{{__('Service Starts')}}</span>
                        </li>
                        <li>
                            <span class="value">{{$service_end}}</span>
                            <span class="label">{{__('Service Ends')}}</span>
                        </li>
                        <li>
                            <span class="value">{{$service_duration}}<small> {{__('mins')}}</small></span>
                            <span class="label">{{__('Service Duration')}}</span>
                        </li>
                        @php
                            $term = get_term('id', $post['service']);
                        @endphp

                        @if(isset($term->term_title))
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

                @if(isset($enable_cancellation) && $enable_cancellation == 'on')
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
                    <h2 class="section-title">{{__('Beauty Service On Map')}}</h2>
                    <div class="section-content">
                        <div class="map-single" data-lat="{{$post['location_lat']}}"
                             data-lng="{{$post['location_lng']}}"></div>
                    </div>
                </section>

                @include('Frontend::services.beauty.single.review')

            </div>
            <div class="col-lg-4">
                <div class="siderbar-single">
                    @include('Frontend::services.beauty.single.booking-form')
                    @include('Frontend::components.sections.partner-info')
                </div>
            </div>
        </div>
    </div>
    @include('Frontend::services.beauty.single.related')
    @include('Frontend::services.beauty.single.nearby')
@stop

