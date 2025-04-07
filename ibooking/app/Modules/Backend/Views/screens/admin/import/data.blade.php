@extends('Backend::layouts.master')

@section('title', __('Import Demo Data'))

@section('content')
    <div class="layout-top-spacing">
        <div class="row">
            <div class="col-lg-12">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <h6>{{__('Import Demo Data')}}</h6>
                    </div>
                    <div class="widget-content widget-content-area pl-2 pr-2">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="alert alert-warning">{{__('Your old data will be reset after importing demo data. Please be careful!')}}</div>
                                <form method="POST" id="gmz-import-data-form" action="{{dashboard_url('import-data')}}">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                                    <div class="form-group">
                                        <label>{!! __('Please enter <b>"Import"</b> text into textbox below') !!}</label>
                                        <input type="text" name="check_text" class="form-control"/>
                                    </div>
                                    @if(session()->has('error'))
                                        <div class="alert alert-warning">
                                            {{session('error')}}
                                        </div>
                                    @endif
                                    @if(session()->has('success'))
                                        <div class="alert alert-success">
                                            {{session('success')}}
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-primary">{{__('Import now!')}}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop