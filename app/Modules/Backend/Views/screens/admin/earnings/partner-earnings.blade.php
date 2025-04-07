@extends('Backend::layouts.master')

@section('title', __('Partner\'s Earning'))

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
       'custom-sweetalerts',
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
                            <h4>{{__('Partner\'s Earning')}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mb-4 mt-4">
                @if($data->total() > 0)
                    <table class="multi-table table table-striped table-bordered table-hover non-hover overflow-hidden w-100" data-plugin="footable">
                        <thead>
                        <tr>
                            <th style="min-width: 90px" data-breakpoints="xs">{{__('ID')}}</th>
                            <th>{{__('User Name')}}</th>
                            <th data-breakpoints="xs sm md">{{__('User Email')}}</th>
                            <th>{{__('Total')}}</th>
                            <th data-breakpoints="xs">{{__('Net Earnings')}}</th>
                            <th data-breakpoints="xs">{{__('Balance')}}</th>
                            <th data-breakpoints="xs sm md">
                                {{__('Action')}}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->items() as $key => $item)
                            <tr>
                                <td>#{{$item['id']}}</td>
                                <td>{{get_user_name($item['user_id'])}}</td>
                                <td>{{get_user_email($item['user_id'])}}</td>
                                <td>{{convert_price($item['total'])}}</td>
                                <td>{{convert_price($item['net_earnings'])}}</td>
                                <td>{{convert_price($item['balance'])}}</td>
                                <td>
                                    <a href="{{dashboard_url("analytics")}}/{{$item['user_id']}}/" class="btn btn-sm btn-outline-primary">{{__('Analytic')}}</a>
                                    <a href="{{dashboard_url("withdrawal")}}/{{$item['user_id']}}/" class="btn btn-sm btn-outline-info">{{__('Withdrawal')}}</a>
                                </td>
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
@stop