@extends('Frontend::layouts.user')

@section('content')
    <h1 class="">{{__('Password Recovery')}}</h1>
    <p class="signup-link recovery">{{__('Enter your email and instructions will sent to you!')}}</p>
    <form class="text-left gmz-form-action" action="{{url('password/email')}}" method="POST">
        @include('Backend::components.loader')
        <div class="form">

            <div id="email-field" class="field-wrapper input">
                <div class="d-flex justify-content-between">
                    <label for="email">{{__('EMAIL')}}</label>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                <input id="email" name="email" type="text" class="form-control gmz-validation" data-validation="required" value="" placeholder="{{__('Email')}}">
            </div>

            <div class="gmz-message"></div>

            <div class="d-sm-flex justify-content-between">

                <div class="field-wrapper">
                    <button type="submit" class="btn btn-primary" value="">{{__('Reset')}}</button>
                </div>
            </div>

        </div>
    </form>
@endsection