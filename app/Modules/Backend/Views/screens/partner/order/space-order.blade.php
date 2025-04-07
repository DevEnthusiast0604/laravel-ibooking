@extends('Backend::layouts.master')

@section('title', __('Space Orders'))

@php
    admin_enqueue_styles([
        'gmz-datatables',
        'gmz-dt-global',
        'gmz-dt-multiple-tables',
        'sweetalerts',
        'sweetalerts2',
        'footable'
    ]);
    admin_enqueue_scripts([
       'gmz-datatables',
       'sweetalerts2',
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
                            <h4>{{__('Space Orders')}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            @include('Backend::screens.' . get_user_role() . '.order.section-filter')
            <div class="table-responsive mb-4 mt-4">
                @php
                    $params = request()->route()->parameters();
                    $current_url = dashboard_url('order/'.$params['post_type'].'?');
                @endphp
                @if($allPosts->total() > 0)
                    <table id="gmz_table" class="multi-table table table-striped table-bordered table-hover non-hover w-100" data-plugin="footable">
                        <thead>
                        <tr>
                            <th data-breakpoints="xs sm md">
                                <a class="table-sort" href="{{$current_url . build_query_sort('id')}}">
                                    <span>{{__('ID')}}</span>
                                    <span class="icon-sort">
                                        {!! get_icon_sort('id') !!}
                                    </span>
                                </a>

                            </th>
                            <th>{{__('Service')}}</th>
                            <th data-breakpoints="xs sm md">{{__('Number')}}</th>
                            <th data-breakpoints="xs sm md">
                                <a class="table-sort" href="{{$current_url . build_query_sort('start_date')}}">
                                    <span>{{__('Booking')}}</span>
                                    <span class="icon-sort">
                                        {!! get_icon_sort('start_date') !!}
                                    </span>
                                </a>
                            </th>
                            <th>{{__('Total')}}</th>
                            <th data-breakpoints="xs">{{__('Status')}}</th>

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
                                $post = get_post($data['post_id'], $item['post_type']);
                            @endphp
                            <tr>
                                <td>
                                    #{{$item['sku']}}
                                </td>
                                <td>
                                    <span class="order-posttype {{$data['post_type']}}">{{ucfirst($data['post_type'])}}</span>
                                    <strong class="mb-1 clearfix mt-05">
                                        <a href="{{get_the_permalink($post['post_slug'], $data['post_type'])}}">{{get_translate($post['post_title'])}}</a>
                                    </strong>
                                </td>
                                <td>{{$item['number']}}</td>
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
                                        $data_params = base64_encode(json_encode($params));
                                        $action_change_status = dashboard_url('update-status-order');
                                    @endphp
                                    {{--Action button--}}
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-warning gmz-open-modal w-100 btn-sm"
                                                data-target="#gmzOrderDetailModal"
                                                data-action="{{dashboard_url('get-order-detail')}}"
                                                data-params="{{$data_params}}">
                                            {{__('Detail')}}
                                        </button>
                                    </div>
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