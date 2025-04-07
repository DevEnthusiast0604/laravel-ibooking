@extends('Backend::layouts.master')

@section('title', $title)

@php
    admin_enqueue_styles('gmz-steps');
    admin_enqueue_scripts('gmz-steps');
    admin_enqueue_styles('gmz-custom-tab');
@endphp

@section('content')

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">

            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <h4 class="mb-0">{{$title}}</h4>
                                @if(!$new)
                                    @php
                                        if($serviceData['status'] == 'pending'){
                                            $class_status = 'text-warning';
                                        }elseif($serviceData['status'] == 'draft'){
                                            $class_status = 'text-danger';
                                        }else{
                                            $class_status = 'text-success';
                                        }
                                    @endphp
                                    <p class="mb-0 {{$class_status}} ml-1">({{ucfirst($serviceData['status'])}})</p>
                                @endif
                            </div>
                            <div>
                                <a href="{{dashboard_url('all-rooms?hotel_id='. $serviceData['id'])}}" class="btn btn-warning btn-sm">{{__('Manage Rooms')}}</a>
                                <a href="{{get_hotel_permalink($serviceData['post_slug'])}}" id="post-preview" class="btn btn-primary btn-sm" target="_blank">{{__('Preview')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $settings = admin_config('settings', GMZ_SERVICE_HOTEL);
                $action = dashboard_url('save-hotel');
            @endphp

            @include('Backend::settings.meta')

        </div>
        @php
            $post_type = GMZ_SERVICE_HOTEL;
        @endphp
        @include('Backend::screens.admin.seo.components.append')
    </div>
@stop