@extends('Backend::layouts.master')

@section('title', __('Menu'))

@php
    admin_enqueue_scripts('jquery-ui');
    admin_enqueue_styles('jquery-ui');
    admin_enqueue_scripts('nested-sort-js');
@endphp

@section('content')

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">

            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">{{__('Menu')}}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4"/>

            <div class="widget-header">
                @include('Backend::screens.admin.menu.topbar')
                <div class="row">
                    <div class="col-lg-4 gmz-add-menu-box-wrapper">
                        @include('Backend::screens.admin.menu.sidebar')
                    </div>
                    <div class="col-lg-8">
                        @include('Backend::screens.admin.menu.content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

