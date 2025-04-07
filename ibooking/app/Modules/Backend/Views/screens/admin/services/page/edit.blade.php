@extends('Backend::layouts.master')

@section('title', $title)

@php
    admin_enqueue_styles('gmz-steps');
    admin_enqueue_scripts('gmz-steps');
@endphp

@section('content')

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow gmz-new-page">

            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <h4>{{$title}}</h4>
                                @if(!$new)
                                    <p class="mb-0 {{($serviceData['status'] == 'draft') ? 'text-danger' : 'text-success'}} ml-1">({{ucfirst($serviceData['status'])}})</p>
                                @endif
                            </div>
                            <div>
                                <a href="{{get_page_permalink($serviceData['post_slug'])}}" id="post-preview" class="btn btn-primary btn-sm" target="_blank">{{__('Preview')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $settings = admin_config('settings', 'page');
                $action = dashboard_url('save-page');
            @endphp

            @include('Backend::settings.meta')
        </div>
        @php
            $post_type = 'page';
        @endphp
        @include('Backend::screens.admin.seo.components.append')
    </div>
@stop