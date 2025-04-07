@extends('Backend::layouts.master')

@section('title', __('Settings'))

@php
    admin_enqueue_styles('gmz-custom-tab');
@endphp

@section('content')

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow gmz-settings">
            @php
                $settings = get_config_settings();
            @endphp
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4 class="mb-3 mt-2">{{__('Settings Page')}}</h4>
                    </div>
                </div>
            </div>

            @include('Backend::settings.option')

        </div>
    </div>
@stop

