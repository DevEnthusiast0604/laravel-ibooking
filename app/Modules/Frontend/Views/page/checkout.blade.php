@extends('Frontend::layouts.master')

@section('title', __('Checkout'))
@section('class_body', 'page page-checkout')

@php
    enqueue_scripts([
        'stripe',
        'stripe-client',
    ]);
    $cart = Cart::inst()->getCart();
    //If payment is defective and needs payment again
    $order_default = [
        "order_token" => "",
        "first_name" => "",
        "last_name" => "",
        "email" => "",
        "phone" => "",
        "address" => "",
        "city" => "",
        "country" => "",
        "postcode" => "",
        "note" => null,
    ];
    if(!empty($order_data)){
      $order_default = gmz_parse_args($order_data,$order_default);
    }
@endphp

@section('content')
    @php
        the_breadcrumb([], 'page', [
            'title' => __('Checkout')
        ]);
    @endphp

    @if(session()->has('message'))
        <div class="container">
            <div class="alert alert-warning alert-dismissible">
                {{ session('message') }}
            </div>
        </div>
    @endif

    <div class="container">
        @if(!empty($cart))
            <div class="row">
                <div class="col-lg-8 cart-user-form">
                    <div class="page-heading checkout">
                        <h1>{{__('Checkout')}}</h1>
                    </div>

                    <form class="gmz-form-action" id="checkout-form" method="post" action="{{url('checkout')}}">
                        @include('Frontend::components.loader')
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="first_name">{{__('First Name')}}<span class="required">*</span></label>
                                <input type="text" name="first_name" id="first_name" class="form-control gmz-validation"
                                       data-validation="required" value="{{$order_default['first_name']}}" required/>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="last-name">{{__('Last Name')}}</label>
                                <input type="text" name="last_name" id="last-name" class="form-control" value="{{$order_default['last_name']}}" required/>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="email">{{__('Email')}}<span class="required">*</span></label>
                                <input type="email" name="email" id="email" class="form-control gmz-validation"
                                       data-validation="required" value="{{$order_default['email']}}" required/>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="phone">{{__('Phone')}}<span class="required">*</span></label>
                                <input type="text" name="phone" id="phone" class="form-control gmz-validation"
                                       data-validation="required" value="{{$order_default['phone']}}" required/>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="adress">{{__('Address')}}<span class="required">*</span></label>
                                <textarea id="address" class="form-control gmz-validation" data-validation="required"
                                          rows="3" name="address">{{$order_default['address']}}</textarea>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="country">{{__('Country')}}</label>
                                @php
                                    $countries = list_countries();
                                @endphp
                                <select id="country" name="country" class="form-control">
                                    @foreach($countries as $key => $val)
                                        @if($order_default['country'] == $key)
                                            <option value="{{$key}}" selected>{{$val}}</option>
                                            @continue
                                        @endif
                                        <option value="{{$key}}">{{$val}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="city">{{__('City')}}</label>
                                <input type="text" name="city" id="city" class="form-control" value="{{$order_default['city']}}"/>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="postcode">{{__('Postcode')}}</label>
                                <input type="text" name="postcode" id="postcode" class="form-control" value="{{$order_default['postcode']}}"/>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="note">{{__('Note')}}</label>
                                <textarea id="note" class="form-control" rows="7" name="note">{{$order_default['note']}}</textarea>
                            </div>
                            @if(!empty($order_default['order_token']))
                                {{-- 2nd payment --}}
                                <input type="hidden" name="order_token" value="{{$order_default['order_token']}}">
                            @endif
                        </div>
                        @php
                            $payments = Gateway::inst()->getPaymentsAvailable();
                        @endphp
                        <div class="payment-form">
                            <p class="payment-form__title">{{__('Select Payment Method')}}</p>
                            @if(!empty($payments))
                                @foreach($payments as $item)
                                    <div class="payment-item">
                                        <label>
                                        <span class="check-payment @if($loop->index == 0) active @endif">
                                            <input type="radio" name="payment_method"
                                                   id="payment-method-{{$item['id']}}" value="{{$item['id']}}"
                                                   @if($loop->index == 0) checked @endif>
                                        </span>
                                            <span class="payment-title">{{esc_html(get_translate($item['name']))}}</span>
                                            @if(!empty($item['logo']))
                                                <img class="payment-image" src="{{esc_url($item['logo'])}}"
                                                     alt="{{$item['id']}}">
                                            @endif
                                        </label>
                                        <div class="card card-top-arrow"
                                             @if($loop->index == 0) style="display: block" @endif>
                                            <div class="card-body">
                                                {!! balance_tags($item['html']) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>{{__('No payments available')}}</p>
                            @endif
                        </div>

                        <label class="checkbox-inline mt-5">
                            <input type="checkbox" name="agree" checked value="1">
                            <span></span>
                            {!! sprintf(__('I accept %s'), '<a href="'. get_term_link() .'" class="link">'. __('Terms and Conditions') .'</a>') !!}
                        </label>

                        <div class="gmz-message"></div>

                        <button type="submit" class="btn btn-primary btn-checkout">{{__('CHECKOUT')}}</button>
                        @if(!is_user_login())
                        <p class="mt-3"><small><i>{{__('An account will be created automatic by your email.')}}</i></small></p>
                        @endif
                    </form>
                </div>
                <div class="col-lg-4 cart-info-wrapper">
                    @php
                        $view = apply_filter('gmz_cart_item_view', 'Frontend::services.' . $cart['post_type'] . '.cart-item', $cart);
                    @endphp
                    @include($view)
                </div>
            </div>
        @else
            <div class="alert alert-danger">{{__('Cart is empty')}}</div>
        @endif
    </div>
@stop

