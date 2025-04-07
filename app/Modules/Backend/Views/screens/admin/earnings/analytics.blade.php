@extends('Backend::layouts.master')

@section('title', __('Analytics'))

@php
    admin_enqueue_styles([
        'apexcharts',
        'flatpickr',
        'perfect-scrollbar',
        'modules-widgets',
    ]);
    admin_enqueue_scripts([
        'apexcharts',
        'flatpickr',
        'perfect-scrollbar',
        'gmz-widget'
    ]);
@endphp
@section('content')

    <div class="layout-top-spacing">
        <div class="statbox box box-shadow">
           <h4>{{__('Analytics')}}
               @if($userID == -1)
                   <span class="h6">{{__('for all user')}}</span>
               @elseif(is_admin())
                   <span class="h6">{{__('for')}} {{get_user_name($userID)}}</span>
               @endif
           </h4>
        </div>
    </div>
    <div class="row sales">
        <div class="col-xl-6 col-md-8 col-12 layout-spacing">
            <!--widgetIncomeStatistics-->
            <div class="getWidget d-none"
                 data-action="{{dashboard_url('get-widget')}}"
                 data-widget="widgetIncomeStatistics"
                 data-id="{{$userID}}">
            </div>
        </div>

        <div class="col-xl-6 col-md-8 col-12 layout-spacing">
            <!--widgetIncomeStatistics-->
            <div class="getWidget d-none"
                 data-action="{{dashboard_url('get-widget')}}"
                 data-widget="widgetBalance"
                 data-id="{{$userID}}">
            </div>
        </div>

        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <!--widgetRevenue-->
            <div class="getWidget d-none"
                 data-action="{{dashboard_url('get-widget')}}"
                 data-widget="widgetRevenue"
                 data-id="{{$userID}}">
            </div>
        </div>
        <div class="col-xl-4 col-md-6 layout-spacing">
            <!--widgetTransactions-->
            <div class="getWidget d-none"
                 data-action="{{dashboard_url('get-widget')}}"
                 data-widget="widgetTransactions"
                 data-id="{{$userID}}">
            </div>
        </div>
    </div>

    <div class="row sales">

    </div>
@stop

