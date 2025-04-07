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
            <div class="widget-one" id="widgetTotalOrders" data-json="[0,0,0,0,0,0,0]" data-name="Sales">
                <div class="widget-content">
                    <div class="w-numeric-value">
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        </div>
                        <div class="w-content">
                            <span class="w-value">{{$total_order}}</span>
                            <span class="w-numeric-title">{{__('Total Orders')}}</span>
                        </div>
                    </div>
                    <div class="w-chart" style="position: relative;">
                        <div id="total-orders" style="min-height: 130px;"></div>
                    </div>
                    <a href="{{dashboard_url('my-orders')}}" class="btn btn-default ml-4 mb-4">{{__('View Detail')}}</a>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <!--widgetTotalEarnings-->
            <div class="widget-one gmz-bg-orange" id="widgetTotalCommission" data-json="[0,0,0,0,0,0,0]" data-name="Sales" data-symbol="{&quot;position&quot;:&quot;left&quot;,&quot;symbol&quot;:&quot;$&quot;}">
                <div class="widget-content">
                    <div class="w-numeric-value">
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                        </div>
                        <div class="w-content">
                            <span class="w-value">{{$total_notify}}</span>
                            <span class="w-numeric-title">{{__('Total Notifications')}}</span>
                        </div>
                    </div>
                    <div class="w-chart" style="position: relative;">
                        <div id="total-commission" style="min-height: 130px;"></div>
                    </div>
                    <a href="{{dashboard_url('notifications')}}" class="btn btn-default ml-4 mb-4">{{__('View Detail')}}</a>
                </div>
            </div>
        </div>
    </div>
@stop