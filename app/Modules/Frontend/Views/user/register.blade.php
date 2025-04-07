@extends('Frontend::layouts.user')

@php
    admin_enqueue_styles('gmz-checkbox');
@endphp

@section('content')
    <h1 class="">{{__('Register')}}</h1>
    <p class="signup-link register">{{__('Already have an account?')}} <a href="{{url('login')}}">{{__('Log in')}}</a></p>
    <form class="text-left gmz-form-action" action="{{url('register')}}" method="POST">
        @include('Backend::components.loader')
        <div class="form">

            <div class="row">
                <div class="col-lg-6">
                    <div id="first_name-field" class="field-wrapper input">
                        <label for="first_name">{{__('FIRST NAME')}}</label>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <input id="first_name" name="first_name" type="text" class="form-control gmz-validation" data-validation="required" placeholder="{{__('First Name')}}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div id="last_name-field" class="field-wrapper input">
                        <label for="last_name">{{__('LAST NAME')}}</label>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <input id="last_name" name="last_name" type="text" class="form-control gmz-validation" data-validation="required" placeholder="{{__('Last Name')}}">
                    </div>
                </div>
            </div>

            <div id="email-field" class="field-wrapper input">
                <label for="email">{{__('EMAIL')}}</label>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign register"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                <input id="email" name="email" type="text" value="" class="form-control gmz-validation" data-validation="required" placeholder="{{__('Email')}}">
            </div>

            <div id="password-field" class="field-wrapper input mb-2">
                <div class="d-flex justify-content-between">
                    <label for="password">{{__('PASSWORD')}}</label>
                    <a href="{{url('password/reset')}}" class="forgot-pass-link">{{__('Forgot Password?')}}</a>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <input id="password" name="password" type="password" class="form-control gmz-validation" data-validation="required" placeholder="{{__('Password')}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </div>

            <div class="field-wrapper terms_condition">
                <div class="n-chk">
                    <label class="new-control new-checkbox checkbox-primary">
                        <input type="checkbox" name="agree_field" value="1" id="agree-term" class="new-control-input gmz-validation" data-validation="required">
                        <span class="new-control-indicator"></span><span>{!! sprintf(__('I agree to the %s'), '<a href="'. get_term_link() .'">  '. __('terms and conditions') .' </a>') !!}</span>
                    </label>
                </div>

            </div>

            <div class="gmz-message"></div>

            <div class="d-sm-flex justify-content-between">
                <div class="field-wrapper">
                    <button type="submit" class="btn btn-primary" value="">{{__('Register')}}</button>
                </div>
            </div>

            @if(is_social_login_enable('facebook') || is_social_login_enable('google'))
                <div class="division">
                    <span>{{__('OR')}}</span>
                </div>

                <div class="social">
                    @if(is_social_login_enable('facebook'))
                        <a href="{{ url('/auth/redirect/facebook') }}" class="btn social-fb">
                            {!! get_icon('icon_system_facebook') !!}
                            <span class="brand-name">{{__('Facebook')}}</span>
                        </a>
                    @endif
                    @if(is_social_login_enable('google'))
                        <a href="{{ url('/auth/redirect/google') }}" class="btn social-github">
                            {!! get_icon('icon_system_google') !!}
                            <span class="brand-name">{{__('Google')}}</span>
                        </a>
                    @endif
                </div>
            @endif

        </div>
    </form>
@endsection