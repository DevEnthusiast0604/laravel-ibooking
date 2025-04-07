@extends('Frontend::layouts.user')

@section('content')
    <h1 class="">{{__('Sign In')}}</h1>
    <p class="">{{__('Log in to your account to continue.')}}</p>

    <form class="text-left gmz-form-action" action="{{url('login')}}" method="POST">
        @include('Backend::components.loader')
        <div class="form">
            <div id="username-field" class="field-wrapper input">
                <label for="username">{{__('EMAIL')}}</label>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <input id="username" name="email" type="text" class="form-control gmz-validation" data-validation="required" placeholder="{{__('Your email')}}">
            </div>

            <div id="password-field" class="field-wrapper input mb-2">
                <div class="d-flex justify-content-between">
                    <label for="password">{{__('PASSWORD')}}</label>
                    <a href="{{url('password/reset')}}" class="forgot-pass-link">{{__('Forgot Password?')}}</a>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <input id="password" name="password" type="password" class="form-control gmz-validation" data-validation="required" placeholder="{{__('Your password')}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </div>

            <div class="gmz-message"></div>

            <div class="d-sm-flex justify-content-between">
                <div class="field-wrapper">
                    <button type="submit" class="btn btn-primary" value="">{{__('Log In')}}</button>
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

            <p class="signup-link">{{__('Not registered ?')}} <a href="{{url('register')}}">{{__('Create an account')}}</a></p>

        </div>
    </form>
@endsection