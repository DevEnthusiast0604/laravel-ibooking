@extends('Frontend::layouts.user')

@section('content')
    <h1 class="">{{__('Password Recovery')}}</h1>
    <p class="signup-link recovery">{{__('Create new password with in the this form!')}}</p>
    <form class="text-left gmz-form-action" action="{{url('password/reset')}}" method="POST">
        @include('Backend::components.loader')
        <input type="hidden" name="token" value="{{$token}}" />
        <div class="form">

            <div id="email-field" class="field-wrapper input">
                <div class="d-flex justify-content-between">
                    <label for="email">{{__('EMAIL')}}</label>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                <input id="email" name="email" readonly type="text" class="form-control gmz-validation" data-validation="required" value="{{$email}}" placeholder="{{__('Email')}}">
            </div>

            <div id="password-field" class="field-wrapper input">
                <div class="d-flex justify-content-between">
                    <label for="email">{{__('PASSWORD')}}</label>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <input id="password" name="password" type="password" class="form-control gmz-validation" data-validation="required" placeholder="{{__('New password')}}">
            </div>

            <div id="re-password-field" class="field-wrapper input">
                <div class="d-flex justify-content-between">
                    <label for="email">{{__('PASSWORD')}}</label>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <input id="confirm-password" name="password_confirmation" type="password" class="form-control gmz-validation" data-validation="required" placeholder="{{__('Password confirm')}}">
            </div>

            <div class="gmz-message"></div>

            <div class="d-sm-flex justify-content-between">

                <div class="field-wrapper">
                    <button type="submit" class="btn btn-primary" value="">{{__('Reset Password')}}</button>
                </div>
            </div>

        </div>
    </form>
@endsection