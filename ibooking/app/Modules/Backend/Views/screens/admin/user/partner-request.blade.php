<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/9/20
 * Time: 09:54
 */
?>
@extends('Backend::layouts.master')

@section('title', __('Partners Request'))

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
            'userID' => '',
            'userHashing' => gmz_hashing('')
        ];
    @endphp

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">

            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4>{{__('Partners Request')}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mb-4 mt-4">
                @if($allUsers->total() > 0)
                    <table class="multi-table table table-striped table-bordered table-hover non-hover w-100" data-plugin="footable">
                        <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th data-breakpoints="xs sm">{{__('Email')}}</th>
                            <th data-breakpoints="xs sm">{{__('Approve')}}</th>
                            <th data-breakpoints="xs sm">{{__('Address')}}</th>
                            <th class="text-center">{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($allUsers->items() as $key => $item)
                            <tr>
                                <td class="d-flex align-items-center">
                                    <b>{{get_user_name($item->id)}}</b>
                                </td>
                                <td>
                                    {{$item->email}}
                                </td>
                                <td>
                                    @php
                                        $approve_params = [
                                            'userID' => $item->id,
                                            'userHashing' => gmz_hashing($item->id)
                                        ];
                                    @endphp
                                    <label class="gmz-switcher gmz-switcher-action switch s-icons s-outline  s-outline-primary  mb-0"
                                           data-params="{{base64_encode(json_encode($approve_params))}}"
                                           data-confirm="true" data-action="{{dashboard_url('approve-partner')}}">
                                        <input type="checkbox" @if($item->role_id == 2) checked @endif>
                                        <span class="slider round"></span>
                                        <input type="hidden" name="approve_partner" value="@if($item->role_id == 2) on @else off @endif" />
                                    </label>
                                </td>
                                <td>
                                    @if(!empty($item->address))
                                        {{$item->address}}
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $params = [
                                            'userID' => $item->id,
                                            'userHashing' => gmz_hashing($item->id)
                                        ];
                                    @endphp
                                    <a class="btn btn-danger btn-sm gmz-link-action" href="javascript:void(0);" data-confirm="true" data-action="{{dashboard_url('delete-user')}}" data-params="{{base64_encode(json_encode($params))}}" data-remove-el="tr">{{__('Delete')}}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="gmz-pagination">
                        {!! $allUsers->links() !!}
                    </div>

                @else
                    <div class="alert alert-warning">{{__('No data')}}</div>
                @endif
            </div>
        </div>
    </div>
@stop


