<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 11/28/2020
 * Time: 9:36 PM
 */
?>
@extends('Backend::layouts.master')

@section('title', __('Profile'))

@section('content')

<div class="layout-top-spacing profile-page">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <h4>{{__('Your profile')}}</h4>
            <form class="gmz-form-action mt-5" action="{{dashboard_url('update-profile')}}" method="POST" autocomplete="off">
                @include('Backend::components.loader')
            <div class="row">
                <div class="col-xl-8 col-md-12 col-sm-12 col-12">

                        <div class="form">
                            <div class="form-group">
                                <div id="avatar-field" class="field-wrapper input">
                                    @php
                                        $media_url = '';
                                        if(!empty($data['avatar'])){
                                            $media_url = get_attachment_url($data['avatar'], [150, 150]);
                                        }
                                    @endphp
                                    <div class="gmz-field form-group">
                                        <label for="email">{{__('Avatar')}}</label>
                                        <div class="media-wrapper @if(!empty($media_url)) has-media @endif">
                                            <div class="thumbnail" data-toggle="modal" data-target="#gmzMediaModal" data-url="{{dashboard_url('all-media')}}">
                                                <span class="add-icon">+</span>
                                                @if(!empty($media_url))
                                                    <img src="{{$media_url}}" alt="avatar"/>
                                                @endif
                                            </div>
                                            <div class="action d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success" data-toggle="modal" data-target="#gmzMediaModal" data-url="{{dashboard_url('all-media')}}">{{__('Add image')}}</a>
                                                <a href="javascript:void(0)" class="ml-3 text-danger btn-remove d-none">{{__('Remove')}}</a>
                                            </div>
                                            <input type="hidden" name="avatar" class="form-control" value="{{$data['avatar']}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div id="first_name-field" class="field-wrapper input">
                                            <label for="first_name">{{__('First Name')}}</label>
                                            <input id="first_name" name="first_name" type="text" value="{{$data['first_name']}}" class="form-control gmz-validation" data-validation="required" placeholder="{{__('First Name')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div id="last_name-field" class="field-wrapper input">
                                            <label for="last_name">{{__('Last Name')}}</label>
                                            <input id="last_name" name="last_name" type="text" value="{{$data['last_name']}}" class="form-control gmz-validation" data-validation="required" placeholder="{{__('Last Name')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div id="email-field" class="field-wrapper input">
                                            <label for="email">{{__('Email')}}</label>
                                            <input id="email" name="email" readonly="" type="text" value="{{$data['email']}}" class="form-control" placeholder="{{__('Email')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div id="phone-field" class="field-wrapper input">
                                            <label for="phone">{{__('Phone')}}</label>
                                            <input class="form-control" type="text" name="phone" id="phone" value="{{$data['phone']}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @action('gmz_profile_phone_after', $data)

                            <div class="form-group">
                                <div id="address-field" class="field-wrapper input">
                                    <label for="address">{{__('Address')}}</label>
                                    <textarea rows="2" id="address" name="address" class="form-control" placeholder="{{__('Address')}}">{{$data['address']}}</textarea>
                                </div>
                            </div>

                            @action('gmz_profile_address_after', $data)

                            <div class="form-group">
                                <div id="address-field" class="field-wrapper input">
                                    <label for="address">{{__('Description')}}</label>
                                    <textarea rows="5" id="description" name="description" class="form-control" placeholder="{{__('Description')}}">{{$data['description']}}</textarea>
                                </div>
                            </div>

                            @action('gmz_profile_form_after', $data)

                            <p class="mt-5"><b>{{__('Leave empty if you don\'t want to change password')}}</b></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">{{__('Password')}}</label>
                                        <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password-confirm">{{__('Confirm Password')}}</label>
                                        <input id="password-confirm" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                                    </div>
                                </div>
                            </div>

                            <div class="gmz-message"></div>

                            <div class="d-sm-flex justify-content-between mt-3 mb-3">
                                <div class="field-wrapper">
                                    <button type="submit" class="btn btn-primary" value="">{{__('Save changes')}}</button>
                                </div>
                            </div>

                        </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@stop

