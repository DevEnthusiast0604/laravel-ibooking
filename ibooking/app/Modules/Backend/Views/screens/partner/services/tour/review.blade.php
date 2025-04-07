@extends('Backend::layouts.master')

@section('title', __('Tour Reviews'))

@php
    admin_enqueue_styles([
        'gmz-datatables',
        'gmz-dt-global',
        'gmz-dt-multiple-tables',
        'footable'
    ]);
    admin_enqueue_scripts([
        'gmz-datatables',
        'footable'
    ]);
@endphp

@section('content')

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">

            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4>{{__('Tour Reviews')}}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="filter-action pl-2 mt-3">
                @php
                    $status = request()->get('status', '');
                    if(!in_array($status, ['publish', 'pending'])){
                        $status = '';
                    }
                @endphp
                <a href="{{dashboard_url('tour-review')}}" class="@if($status == '') text-primary font-weight-bold @endif mr-2">{{__('All')}}</a>
                <a href="{{dashboard_url('tour-review?status=publish')}}" class="@if($status == 'publish') text-primary font-weight-bold @endif mr-2">{{__('Publish')}}</a>
                <a href="{{dashboard_url('tour-review?status=pending')}}" class="@if($status == 'pending') text-primary font-weight-bold @endif">{{__('Pending')}}</a>
            </div>

            <div class="table-responsive mb-4 mt-4">
                @if($allPosts->total() > 0)
                    <table class="multi-table table table-striped table-bordered table-hover non-hover overflow-hidden w-100" data-plugin="footable">
                        <thead>
                        <tr>
                            <th>{{__('Info')}}</th>
                            <th data-breakpoints="xs sm">{{__('Status')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($allPosts->items() as $key => $item)
                            @php
                                $post = get_post($item->post_id, $item->post_type);
                                $post_title = get_translate($item->post_title);
                            @endphp
                            <tr>
                                <td style="max-width: 450px">
                                    <p>{{__('Title: ')}} &nbsp; <b>{{$item->comment_title}}</b></p>
                                    <p>{{__('In:')}} &nbsp;<b><a href="{{get_tour_permalink($post['post_slug'])}}" target="_blank">{{get_translate($post['post_title'])}}</a></b></p>
                                    <p>
                                   {{$item->comment_content}}
                                    </p>
                                    <p>{{__('By ')}} <b>{{$item->comment_name}} ({{$item->comment_email}})</b>
                                    </p>

                                </td>

                                <td class="align-middle">
                                    {{ucfirst($item->status)}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="gmz-pagination">
                        {!! $allPosts->appends($_GET)->links() !!}
                    </div>

                @else
                    <div class="alert alert-warning">{{__('No data')}}</div>
                @endif
            </div>



        </div>
    </div>
@stop

