@extends('Backend::layouts.master')

@section('title', __('My Orders'))

@php
    admin_enqueue_styles([
        'gmz-datatables',
        'gmz-dt-global',
        'gmz-dt-multiple-tables',
        'footable'
    ]);
    admin_enqueue_scripts([
       'gmz-datatables',
       'gmz-table',
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
                            <h4>{{__('My Orders')}}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-4 mt-4">
                @php
                    $current_url = dashboard_url('my-orders?');
                @endphp
                @if($allPosts->total() > 0)
                    <table id="gmz_table" class="multi-table table table-striped table-bordered table-hover non-hover overflow-hidden w-100" data-plugin="footable">
                        <thead>
                        <tr>
                            <th width="90px" style="min-width: 90px" data-breakpoints="xs">
                                <a class="table-sort" href="{{$current_url . build_query_sort('id')}}">
                                    <span>{{__('ID')}}</span>
                                    <span class="icon-sort">
                                        {!! get_icon_sort('id') !!}
                                    </span>
                                </a>
                            </th>
                            <th>{{__('Service')}}</th>
                            <th data-breakpoints="xs sm md">
                                <a class="table-sort "  href="{{$current_url . build_query_sort('start_date')}}">
                                    <span>{{__('Booking')}}</span>
                                    <span class="icon-sort bs-tooltip bs-tooltip-sm" title="{{__("sort by start date")}}">
                                        {!! get_icon_sort('start_date') !!}
                                    </span>
                                </a>
                            </th>
                            <th>{{__('Total')}}</th>
                            <th data-breakpoints="xs">
                                <a class="table-sort"  href="{{$current_url . build_query_sort('status')}}">
                                    <span>{{__('Status')}}</span>
                                    <span class="icon-sort">
                                        {!! get_icon_sort('status') !!}
                                    </span>
                                </a>
                            </th>
                            <th data-breakpoints="xs sm md">
                                <a class="table-sort" href="{{$current_url . build_query_sort('updated_at')}}">
                                    <span>{{__('Date')}}</span>
                                    <span class="icon-sort">
                                        {!! get_icon_sort('updated_at') !!}
                                    </span>
                                </a>
                            </th>
                            <th data-breakpoints="xs">
                                {{__('Action')}}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($allPosts->items() as $key => $item)
                            @php
                                $data = json_decode($item['checkout_data'], true);
                                $postData = [];
                                $postIds = [];
                                if ($data['post_id'] == 0 && !empty($data['service'])){
                                    $postIds = explode(',', $data['service']);
                                }else{
                                    $postIds[] = $data['post_id'];
                                }
                                foreach ($postIds as $s){
                                    $post = get_post($s, $item['post_type']);
                                    $postData[] = [
                                        'title' => get_translate($post['post_title']),
                                        'url' => get_the_permalink($post['post_slug'], $data['post_type'])
                                    ];
                                }
                            @endphp
                            <tr>
                                <td>
                                    #{{$item['id']}}
                                </td>
                                <td>
                                    <span class="order-posttype {{$data['post_type']}}">{{ucfirst($data['post_type'])}}</span>
                                    <strong class="mb-1 clearfix mt-05">
                                        @foreach($postData as $p)
                                            @if($loop->index > 0)
                                                <span>/</span>
                                            @endif
                                            <a href="{{$p['url']}}">{{$p['title']}}</a>
                                        @endforeach
                                    </strong>
                                </td>
                                <td>
                                    <span>{{date(get_date_format(), $item['start_date'])}}</span>
                                    {{ __('to') }}
                                    <span>{{date(get_date_format(), $item['end_date'])}}</span>
                                </td>
                                {{-- Total --}}
                                <td>
                                    {{convert_price($item['total'])}}
                                </td>
                                {{-- Status --}}
                                <td class="td-status" data-status="{{$item['status']}}" data-payment-type="{{$item['payment_type']}}">
                                    <span class="order-status">{!! the_order_status($item['status']) !!}</span>
                                    <span class="order-payment d-block mt-2">
                                        <small>({{get_payment_type($item['payment_type'])}})</small>
                                    </span>
                                </td>
                                {{-- Date :by updated_at--}}
                                <td>{{date(get_date_format(true), strtotime($item['updated_at']))}}</td>
                                {{-- Action --}}
                                <td>
                                    @php
                                        $params['orderID'] = $item['id'];
                                        $params['orderHashing'] = gmz_hashing($item['id']);
                                    @endphp
                                    <a class="gmz-open-modal btn btn-warning btn-sm w-100" href="javascript:void(0);" data-target="#gmzOrderDetailModal" data-action="{{dashboard_url('get-order-detail')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Detail')}}</a>
                                    @action('gmz_my_order_actions', $item)
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="gmz-pagination">
                        {!! $allPosts->withQueryString()->links() !!}
                    </div>

                @else
                    <div class="alert alert-warning">{{__('No data')}}</div>
                @endif
            </div>

        </div>
    </div>

    {{--Modal--}}
    @include('Backend::components.modal.order')
    {{--End Modal--}}
@stop