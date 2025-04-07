@extends('Backend::layouts.master')

@section('title', __('Wishlist'))

@php
    admin_enqueue_styles([
        'gmz-wishlist'
    ]);
    admin_enqueue_scripts([
       'gmz-table',
    ]);
@endphp

@section('content')
    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4>{{__('Wishlist')}}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-filter mt-3 mb-2">
                <form id="form_filter" action="{{current_url()}}" method="get">
                    @php
                        $get_data = request()->all();
                        $post_types = request()->route()->parameters();
                    @endphp
                    @foreach($get_data as $key => $value)
                        <input type="hidden" name="{{$key}}" value="{{$value}}" />
                    @endforeach
                    <div class="form-row">
                        <div class="col mb-2">
                            <div class="filter-action pl-2" id="menu_post_type" data-active="{{$post_types['post_type']}}">
                                @php
                                    $services = get_services_enabled();
                                @endphp
                                @if(count($services) == 1)
                                    <a href="{{dashboard_url("wishlist/$services[0]/")}}" id="post_type_{{$services[0]}}" class="mr-2">{{__(ucwords($services[0]))}}</a>
                                @else
                                    @foreach($services as $service)
                                        <a href="{{dashboard_url("wishlist/$service/")}}" id="post_type_{{$service}}" class="mr-2">{{__(ucwords($service))}}</a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if(!$posts->isEmpty())
                @php
                $search_url = url($post_type . '-search');
                @endphp
                <section class="list-{{$post_type}} pl-2 pr-2">
                    <div class="row">
                        @foreach($posts as $item)
                            <div class="col-lg-4 col-md-4 col-sm-12 wishlist-item">
                                @include('Frontend::services.'. $post_type .'.items.grid-item')
                            </div>
                        @endforeach
                    </div>
                </section>
                <div class="gmz-pagination pl-2 pr-2">
                    {!! $posts->withQueryString()->links() !!}
                </div>
            @else
                <div class="pl-2 pr-2">
                    <div class="alert alert-warning">{{__('No data')}}</div>
                </div>
            @endif

        </div>
    </div>
@stop

