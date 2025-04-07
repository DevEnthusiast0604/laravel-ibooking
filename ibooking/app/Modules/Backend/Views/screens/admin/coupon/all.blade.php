@extends('Backend::layouts.master')

@section('title', __('Coupons'))

@php
    admin_enqueue_styles([
        'gmz-datatables',
        'gmz-dt-global',
        'gmz-dt-multiple-tables',
        'gmz-switches',
        'footable'
    ]);
    admin_enqueue_scripts([
        'gmz-datatables',
        'footable'
    ]);
@endphp

@section('content')

    @php
        $params = [
            'couponID' => '',
            'couponHashing' => gmz_hashing('')
        ];
    @endphp

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">

            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4>{{__('Coupons')}}</h4>
                            <a href="javascript:void(0);" class="btn btn-success gmz-open-modal" data-target="#gmzCouponModal" data-action="{{dashboard_url('get-coupon-form')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Add New')}}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-1 mt-4">
                @if($allCoupons->total() > 0)
                    <table class="multi-table table table-striped table-bordered table-hover non-hover w-100" data-plugin="footable">
                        <thead>
                        <tr>
                            <th>{{__('Code')}}</th>
                            <th data-breakpoints="xs sm md">{{__('Description')}}</th>
                            <th>{{__('Discount(%)')}}</th>
                            <th data-breakpoints="xs sm md">{{__('Date Range')}}</th>
                            <th data-breakpoints="xs">{{__('Status')}}</th>
                            <th data-breakpoints="xs">{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($allCoupons->items() as $key => $item)
                            <tr>
                                <td class="d-flex align-items-center">
                                    <h6 class="mb-0">{{$item['code']}}</h6>
                                </td>

                                <td class="">
                                    {{get_translate($item['description'])}}
                                </td>

                                <td class="">
                                    {{$item['percent']}}%
                                </td>

                                <td class="">
                                    {{date(get_date_format(), $item['start_date'])}}
                                    {!! get_icon('icon_system_arrow_right_short', '#dfdfdf', 15, 15) !!}
                                    {{date(get_date_format(), $item['end_date'])}}
                                </td>

                                <td class="align-middle">
                                    @php
                                        $params = [
                                            'couponID' => $item['id'],
                                            'couponHashing' => gmz_hashing($item['id']),
                                        ];
                                    @endphp
                                    <label class="gmz-switcher gmz-switcher-action switch s-icons s-outline  s-outline-primary  mb-0"
                                           data-params="{{base64_encode(json_encode($params))}}"
                                           data-confirm="true" data-action="{{dashboard_url('change-coupon-status')}}">
                                        <input type="checkbox" value="on" @if( $item['status'] == 'publish') checked @endif>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <div class="dropdown custom-dropdown">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                            <a class="dropdown-item gmz-open-modal" href="javascript:void(0);" data-target="#gmzCouponModal" data-action="{{dashboard_url('get-coupon-form')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Edit')}}</a>
                                            <a class="dropdown-item text-danger gmz-link-action" href="javascript:void(0);" data-confirm="true"  data-action="{{dashboard_url('delete-coupon')}}" data-params="{{base64_encode(json_encode($params))}}" data-remove-el="tr">{{__('Delete')}}</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="gmz-pagination mt-4 d-block">
                        {!! $allCoupons->links() !!}
                    </div>

                @else
                    <div class="alert alert-warning">{{__('No data')}}</div>
                @endif
            </div>

        </div>
    </div>

    {{--Modal--}}
    @include('Backend::components.modal.coupon')
    {{--End Modal--}}
@stop

