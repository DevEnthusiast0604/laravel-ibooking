@extends('Frontend::layouts.master')

@section('title', __('Become A Partner'))
@section('class_body', 'page become-partner')

@php
    enqueue_styles([
        'slick',
        'daterangepicker'
    ]);
    enqueue_scripts([
        'slick',
        'moment',
        'daterangepicker'
    ]);
@endphp

@section('content')
    @php
        the_breadcrumb([], 'page', [
            'title' => __('Become A Partner')
        ]);
    @endphp
    <section class="partner-form">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 partner-form__left">
                    <div class="become-form">
                        <h2 class="title">{{__('Become A Partner')}}</h2>
                        <form class="gmz-form-action" action="{{url('become-a-partner')}}" method="POST">
                            @include('Frontend::components.loader')
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="first-name">{{__('First Name')}}<span class="required">*</span> </label>
                                    <input type="text" name="first_name"  class="form-control gmz-validation" data-validation="required" id="first-name"/>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="last-name">{{__('Last Name')}}</label>
                                    <input type="text" name="last_name"  class="form-control" id="last-name"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">{{__('Email')}}<span class="required">*</span> </label>
                                <input type="text" name="email"  class="form-control gmz-validation" data-validation="required" id="email"/>
                            </div>
                            <div class="form-group">
                                <label for="password">{{__('Password')}}<span class="required">*</span> </label>
                                <input type="password" name="password"  class="form-control gmz-validation" data-validation="required" id="password"/>
                            </div>
                            <div class="form-group">
                                <label for="address">{{__('Address')}}<span class="required">*</span> </label>
                                <textarea name="address" rows="3" class="form-control " id="address"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="agree" value="1">
                                    <span></span>
                                    {!! sprintf(__('I accept %s'), '<a href="'. get_term_link() .'" class="link">'. __('Terms and Conditions') .'</a>') !!}
                                </label>
                            </div>
                            <div class="gmz-message"></div>
                            <button type="submit" class="btn btn-primary">{{__('SUBMIT REQUEST')}}</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-7 partner-form__right">
                    <div class="become-intro">
                        <h3>{{__('Why partner on iBooking?')}}</h3>
                        <p>{{__('Join our community and start uploading your properties. We make it simple and secure to host travelers.')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="icon-box">
        <div class="container">
            <h2 class="title">{{__('How does it work?')}}</h2>
            <div class="row">
                <div class="col-lg-4">
                    <div class="item">
                        <div class="number">1</div>
                        <h4 class="main-text">{{__('Set up your property')}}</h4>
                        <p class="sub-text">{{__('Explain what’s unique, show off with photos, and set the right price')}}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="item">
                        <div class="number">2</div>
                        <h4 class="main-text">{{__('Get the perfect match')}}</h4>
                        <p class="sub-text">{{__('We’ll connect you with travelers from home and abroad')}}</p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="item">
                        <div class="number">3</div>
                        <h4 class="main-text">{{__('Start earning')}}</h4>
                        <p class="sub-text">{{__('We’ll help you collect payment, deduct a commission, and send you the balance')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="why-be-partner">
        <div class="container">
            <h2 class="title">{{__('Why be a Partner?')}}</h2>
            <div class="item">
                <div class="left">
                    <img src="{{asset('html/assets/image/page/why-to-partner1.svg')}}" alt="why-to-partner"/>
                </div>
                <div class="right">
                    <h4 class="main-text">
                        {{__('Earn an additional income')}}
                    </h4>
                    <p class="sub-text">{{__('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.')}}</p>
                </div>
            </div>
            <div class="item">
                <div class="left">
                    <img src="{{asset('html/assets/image/page/why-to-partner2.svg')}}" alt="why-to-partner"/>
                </div>
                <div class="right">
                    <h4 class="main-text">
                        {{__('Open your network')}}
                    </h4>
                    <p class="sub-text">{{__('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.')}}</p>
                </div>
            </div>
            <div class="item">
                <div class="left">
                    <img src="{{asset('html/assets/image/page/why-to-partner3.svg')}}" alt="why-to-partner"/>
                </div>
                <div class="right">
                    <h4 class="main-text">
                        {{__('Practice your language')}}
                    </h4>
                    <p class="sub-text">{{__('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.')}}</p>
                </div>
            </div>
        </div>
    </section>
@stop

