<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/9/20
 * Time: 09:54
 */
?>
@extends('Backend::layouts.master')

@section('title', __('All Users'))

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
                            <h4>{{__('All Users')}}</h4>
                            <a href="javascript:void(0);" class="btn btn-success gmz-open-modal" data-target="#gmzUserModal" data-action="{{dashboard_url('get-user-form')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Add New')}}</a>
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
                            <th data-breakpoints="xs sm">{{__('Address')}}</th>
                            <th data-breakpoints="xs sm">{{__('Role')}}</th>
                            <th class="text-center">{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($allUsers->items() as $key => $item)
                            <tr>
                                <td class="d-flex align-items-center">
                                    <h6 class="mb-0">
                                       {{get_user_name($item->id)}}
                                    </h6>
                                </td>
                                <td>
                                    {{$item->email}}
                                </td>
                                <td>
                                    @if(!empty($item->address))
                                    {{$item->address}}
                                    @else
                                    ---
                                    @endif
                                </td>
                                <td>
                                    {{get_user_role($item->id, 'name')}}
                                </td>
                                <td class="text-center">
                                    <div class="dropdown custom-dropdown">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                            @php
                                                $params = [
                                                    'userID' => $item->id,
                                                    'userHashing' => gmz_hashing($item->id)
                                                ];
                                            @endphp
                                            <a class="dropdown-item gmz-open-modal" href="javascript:void(0);" data-target="#gmzUserModal" data-action="{{dashboard_url('get-user-form')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Edit')}}</a>
                                            <a class="dropdown-item text-danger gmz-link-action" href="javascript:void(0);" data-confirm="true" data-action="{{dashboard_url('delete-user')}}" data-params="{{base64_encode(json_encode($params))}}" data-remove-el="tr">{{__('Delete')}}</a>
                                        </div>
                                    </div>
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

    {{--Modal--}}
    @include('Backend::components.modal.user')
    {{--End Modal--}}
@stop


