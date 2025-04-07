@extends('Backend::layouts.master')

@section('title', __('Wishlist'))

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

            <div class="alert alert-warning mt-3">{{__('There are no services enabled!')}}</div>

        </div>
    </div>
@stop

