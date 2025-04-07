@extends('Backend::layouts.master')

@section('title', __('Withdrawal'))

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
       'footable',
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
                            <h4>{{__('Withdrawal')}}</h4>
                            <a href="javascript:void(0);" id="btnWithdrawal" class="btn btn-success"
                               data-action="{{dashboard_url('want-withdrawal')}}"
                               data-balance="{{$wallet['max_withdrawal']}}"
                               data-title="{{__('Withdrawal request')}}"
                               data-desc="{{__('The maximum amount you can withdraw is') . ' ' . convert_price($wallet['max_withdrawal'])}}">
                                {{__('Withdrawal')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mb-4 mt-4">
                @if($data->total() > 0)
                    <table class="multi-table table table-striped table-bordered table-hover non-hover w-100" data-plugin="footable">
                        <thead>
                        <tr>
                            <th style="min-width: 90px" data-breakpoints="xs">{{__('ID')}}</th>
                            <th>{{__('User Name')}}</th>
                            <th data-breakpoints="xs sm md">{{__('User Email')}}</th>
                            <th>{{__('Money')}}</th>
                            <th data-breakpoints="xs sm md">{{__('Create at')}}</th>
                            <th data-breakpoints="xs">{{__('Status')}}</th>
                            @if(is_admin())
                            <th data-breakpoints="xs">{{__('Action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->items() as $key => $item)
                            <tr>
                                <td>#{{$item['id']}}</td>
                                <td>{{get_user_name($item['user_id'])}}</td>
                                <td>{{get_user_email($item['user_id'])}}</td>
                                {{--Withdrawal request--}}
                                <td>{{convert_price($item['withdraw'])}}</td>
                                <td>{{date(get_date_format(true), strtotime($item['created_at']))}}</td>
                                <td class="td-status"
                                    data-status="{{$item['status']}}">
                                    <span class="order-status">{!! get_withdrawal_status($item['status']) !!}</span>
                                </td>
                                @if(is_admin())
                                {{--Action--}}
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-warning gmzModalWithdrawal"
                                                data-action="{{dashboard_url('modal-withdrawal')}}"
                                                data-params="{{$item['user_id']}}"
                                                data-toggle="modal"
                                                data-target="#gmzWithdrawalDetailModal">
                                            {{__('Detail')}}
                                        </button>
                                        <button type="button"
                                                class="btn btn-outline-warning dropdown-toggle dropdown-toggle-split"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-chevron-down">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" style="will-change: transform;">
                                            <a class="dropdown-item btnChangeStatus" href="javascript:void(0);"
                                                data-change-status="{{GMZ_STATUS_ACCEPT}}"
                                                data-action="{{dashboard_url('update-status-withdrawal')}}"
                                                data-params="{{$item['id']}}">
                                                {{__('Accept')}}
                                            </a>
                                            <a class="dropdown-item btnChangeStatus" href="javascript:void(0);"
                                               data-change-status="{{GMZ_STATUS_CANCELLED}}"
                                               data-action="{{dashboard_url('update-status-withdrawal')}}"
                                               data-params="{{$item['id']}}">
                                               {{__('Cancel')}}</a>
                                        </div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="gmz-pagination">
                        {!! $data->links() !!}
                    </div>

                @else
                    <div class="alert alert-warning">{{__('No data')}}</div>
                @endif
            </div>


        </div>
    </div>

    {{--Modal--}}
    @include('Backend::components.modal.withdrawal')
    {{--End Modal--}}
@stop