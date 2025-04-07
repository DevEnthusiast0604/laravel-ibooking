@extends('Backend::layouts.master')

@section('title', __('SEO Tools'))

@php
    admin_enqueue_styles('gmz-custom-tab');
    admin_enqueue_styles('gmz-custom-accordions');
@endphp

@section('content')
    <div class="layout-top-spacing seo-page">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 style="font-size: 23px;">{{__('SEO Tools')}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mb-2 mt-4">
                <form class="gmz-form-action form-translation" action="{{dashboard_url('seo-save-settings')}}" method="POST" data-loader="body" enctype="multipart/form-data">
                    @include('Backend::components.loader')
                <ul class="nav nav-pills mb-3 mt-3" id="icon-pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="icon-pills-general-tab" data-toggle="pill" href="javascript:void(0);" data-target="#icon-pills-general" role="tab" aria-controls="icon-pills-general" aria-selected="true"><i class="fal fa-tachometer-alt"></i> {{__('General')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="icon-pills-page-tab" data-toggle="pill" href="javascript:void(0);" data-target="#icon-pills-page" role="tab" aria-controls="icon-pills-page" aria-selected="false"><i class="fal fa-file-alt"></i> {{__('Static Page')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="icon-pills-content-tab" data-toggle="pill" href="javascript:void(0);" data-target="#icon-pills-content" role="tab" aria-controls="icon-pills-content" aria-selected="false"><i class="fal fa-ballot"></i> {{__('Content Types')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="icon-pills-sitemap-tab" data-toggle="pill" href="javascript:void(0);" data-target="#icon-pills-sitemap" role="tab" aria-controls="icon-pills-sitemap" aria-selected="false"><i class="fal fa-sitemap"></i> {{__('Sitemap')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="icon-pills-webmaster-tab" data-toggle="pill" href="javascript:void(0);" data-target="#icon-pills-webmaster" role="tab" aria-controls="icon-pills-webmaster" aria-selected="false"><i class="fal fa-tools"></i> {{__('Webmaster Tools')}}</a>
                    </li>
                </ul>
                <div class="tab-content" id="icon-pills-tabContent">
                    <div class="tab-pane fade show active" id="icon-pills-general" role="tabpanel" aria-labelledby="icon-pills-general-tab">
                        @include('Backend::screens.admin.seo.general.index')
                    </div>

                    <div class="tab-pane fade" id="icon-pills-page" role="tabpanel" aria-labelledby="icon-pills-page-tab">
                        @include('Backend::screens.admin.seo.page.index')
                    </div>

                    <div class="tab-pane fade" id="icon-pills-content" role="tabpanel" aria-labelledby="icon-pills-content-tab">
                        @include('Backend::screens.admin.seo.content.index')
                    </div>

                    <div class="tab-pane fade" id="icon-pills-sitemap" role="tabpanel" aria-labelledby="icon-pills-sitemap-tab">
                        @include('Backend::screens.admin.seo.sitemap.index')
                    </div>

                    <div class="tab-pane fade" id="icon-pills-webmaster" role="tabpanel" aria-labelledby="icon-pills-webmaster-tab">
                        @include('Backend::screens.admin.seo.webmaster.index')
                    </div>
                </div>
                    <button type="submit" class="btn btn-primary mt-4 mb-0">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
@stop


