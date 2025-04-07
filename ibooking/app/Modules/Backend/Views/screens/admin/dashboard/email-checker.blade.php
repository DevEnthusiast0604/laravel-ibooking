@extends('Backend::layouts.master')

@section('title', __('Email Checker'))

@section('content')
    <div class="layout-top-spacing">
        <div class="row">
            <div class="col-lg-6">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <h4 class="p-0 m-0">{{__('Email Checker')}}</h4>
                    </div>
                    <div class="widget-content widget-content-area pl-2 pr-2 pb-2">
                        <form class="text-left gmz-form-action mt-2" action="{{dashboard_url('checking-email')}}" method="POST" autocomplete="off">
                            @include('Backend::components.loader')
                            <div class="form-group">
                                <div id="email-to-field" class="field-wrapper input">
                                    <label for="email-to">{{__('To')}}</label>
                                    <input id="email-to" name="email_to" type="text" value="" class="form-control gmz-validation" data-validation="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <div id="email-subject-field" class="field-wrapper input">
                                    <label for="email-subject">{{__('Subject')}}</label>
                                    <input id="email-subject" name="email_subject" type="text" value="" class="form-control gmz-validation" data-validation="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <div id="email-content-field" class="field-wrapper input">
                                    <label for="email-content">{{__('Content')}}</label>
                                    <textarea id="email-content" name="email_content" type="text" class="form-control gmz-validation" data-validation="required"></textarea>
                                </div>
                            </div>
                            <div class="gmz-message"></div>

                            <div class="d-sm-flex justify-content-between mt-3">
                                <div class="field-wrapper">
                                    <button type="submit" class="btn btn-primary" value="">{{__('Submit')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop