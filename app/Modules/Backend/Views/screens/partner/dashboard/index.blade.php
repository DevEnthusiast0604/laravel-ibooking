@extends('Backend::layouts.master')

@section('title', __('Dashboard'))

@php
    admin_enqueue_styles([
        'apexcharts',
        'flatpickr',
        'modules-widgets',
    ]);
    admin_enqueue_scripts([
        'apexcharts',
        'flatpickr',
        'gmz-widget'
    ]);
    $user_id = get_current_user_id();
@endphp

@section('content')

    <h5 class="mt-4 mb-4">{{__('Dashboard')}}</h5>

    <div class="row layout-top-spacing sales">

        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <!--widgetTotalOrders-->
            <div class="getWidget d-none"
                 data-action="{{dashboard_url('get-widget')}}"
                 data-widget="widgetTotalOrders"
                 data-id="{{$user_id}}">
            </div>
        </div>

        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <!--widgetTotalEarnings-->
            <div class="getWidget d-none"
                 data-action="{{dashboard_url('get-widget')}}"
                 data-widget="widgetTotalEarnings"
                 data-id="{{$user_id}}">
            </div>
        </div>

        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <!--widgetTotalCommission-->
            <div class="getWidget d-none"
                 data-action="{{dashboard_url('get-widget')}}"
                 data-widget="widgetNetEarnings"
                 data-id="{{$user_id}}">
            </div>
        </div>



        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <!--widgetTotalCommission-->
            <div class="getWidget d-none"
                 data-action="{{dashboard_url('get-widget')}}"
                 data-widget="widgetPendingTask"
                 data-id="{{$user_id}}">
            </div>
        </div>

        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <!--widgetTotalOrders-->
            <div class="getWidget d-none"
                 data-action="{{dashboard_url('get-widget')}}"
                 data-widget="widgetBalance"
                 data-id="{{$user_id}}">
            </div>
        </div>

    </div>
@stop