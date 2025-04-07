@extends('Backend::layouts.master')

@section('title', __('Media'))

@php
    admin_enqueue_styles([
        'gmz-dropzone',
        'gmz-dt-global'
    ]);
    admin_enqueue_scripts('gmz-dropzone');
@endphp

@section('content')

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">

            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{__('All Media')}}</h4>
                    </div>
                </div>
            </div>

            <div id="gmz-media-add-new" class="gmz-media-upload-area">
                <form action="{{ dashboard_url('upload-new-media') }}" method="post" class="gmz-dropzone"
                      id="gmz-upload-form" enctype="multipart/form-data">
                    @include('Backend::components.loader')
                    <div class="fallback">
                        <input name="file" type="file" multiple/>
                    </div>
                    <div class="dz-message text-center needsclick">
                        <i data-feather="upload-cloud"></i>
                        <h3>{{__('Drop files here or click to upload.')}}</h3>
                        <p class="text-muted">
                            <span>{{__('Only JPG, PNG, JPEG, SVG, GIF files types are supported.')}}</span>
                            <span>{{sprintf(__('Maximum file size is %s MB.'), admin_config('max_file_size'))}}</span>
                        </p>
                    </div>
                </form>
            </div>

            <div class="d-flex align-items-center pl-2 mt-3">
                @php
                    $layout = request()->get('layout', 'grid');
                    if(!in_array($layout, ['grid', 'list'])){
                        $layout = 'grid';
                    }
                @endphp
                <div class="filter-layout">
                    <a class="mr-2 @if($layout == 'list') active @endif" href="{{dashboard_url('all-media?layout=list')}}">{!! get_icon('icon_system_list_layout') !!}</a>
                    <a class="@if($layout == 'grid') active @endif" href="{{dashboard_url('all-media')}}">{!! get_icon('icon_system_grid_layout') !!}</a>
                </div>
                @if($layout == 'list')
                    <div class="filter-action gmz-check-all-button">
                        <a class="gmz-bulk-delete-media btn btn-danger btn-sm ml-4" href="javascript:void(0);" data-confirm="true" data-action="{{dashboard_url('bulk-delete-media-item')}}" data-custom-target=".gmz-check-all-item" data-params="">{{__('Bulk Delete')}}</a>
                    </div>
                @endif
            </div>

            @if($layout == 'list')
                @include('Backend::components.media.layout-list')
            @else
                @include('Backend::components.media.layout-grid')
            @endif
        </div>
    </div>

    @include('Backend::components.modal.media')
@stop

