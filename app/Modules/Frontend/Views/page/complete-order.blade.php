@extends('Frontend::layouts.master')

@section('title', __('Thankyou'))
@section('class_body', 'page page-thankyou')


@section('content')
    @php
        the_breadcrumb([], 'page', [
            'title' => __('Thankyou')
        ]);
    @endphp


    <div class="container">
        <div id="confirmation" class="confirmation">
            <img class="confirmation__order-image mb-auto" src="{{asset('images/payment/order.svg')}}" alt="order">

            @if(!empty($notices) && $notices == 'payment_success' && $order)
                <div class="status success">
                    <h1 class="mb-40">{{__('Thanks for your order!')}}</h1>
                    <p>{{__('Woot! You successfully made a payment with')}} {{ucwords($order['payment_type'])}}.</p>
                    <p class="note">{{__('We just sent your receipt to your email address.')}}</p>
                    <div class="pt-20"></div>
                    <a href="{{url('/')}}" class="text-primary pr-4">{{__('Back to home')}}</a>
                    <a href="{{dashboard_url('my-orders')}}" class="text-primary">{{__('My order')}}</a>
                    @action('gmz_complete_order_links', $order)
                </div>
            @elseif(!empty($notices) && $notices == 'payment_incomplete' && $order)
                <div class="status success">
                    <h1 class="mb-40">{{__('Thanks for your order!')}}</h1>
                    <p class="note">{{__('Please make a payment using the details below to complete your order.')}}</p>
                    <p>{{__("We'll email your order completed as soon as we receive your money. This might take a moment but feel free to close this page.")}}</p>
                    @php
                        $desc = get_translate(get_option('payment_'. $order['payment_type'] .'_desc'));
                    @endphp
                    {!! balance_tags($desc) !!}
                    <div class="pt-20"></div>
                    <a href="{{url('/')}}" class="text-primary pr-4">{{__('Back to home')}}</a>
                    <a href="{{dashboard_url('my-orders')}}" class="text-primary">{{__('My order')}}</a>
                    @action('gmz_complete_order_links', $order)
                </div>
            @else
                <div class="status success">
                    <h1 class="mb-40">{{__('No orders found!')}}</h1>
                    <p>{{__('You can review your order in booking history.')}}</p>
                    <div class="pt-20"></div>
                    <a href="{{url('/')}}" class="text-primary pr-4">{{__('Back to home')}}</a>
                    <a href="{{dashboard_url('my-orders')}}" class="text-primary">{{__('My order')}}</a>
                    @action('gmz_complete_order_links', $order)
                </div>
            @endif

        </div>
        @if(!empty($order))
            @php
                $checkout_data = json_decode($order['checkout_data'],true);
                $currency = json_decode($order['currency'],true);
                $post = get_post($order['post_id'],$checkout_data['post_type']);
                if($order['payment_status'] == 1){
                   $payment_status = __('Complete');
                }else{
                   $payment_status = __('Incomplete');
                }
            @endphp
            <div class="row">
                <div class="col-md-6">
                    <div class="user-infomation">
                        <p class="h3 mb-30">{{__('Personal Information')}}</p>
                        <table class="table table-hover">
                            <tr>
                                <td width="200">{{__('First Name')}}</td>
                                <td>{{esc_html($order['first_name'])}}</td>
                            </tr>
                            @if(!empty($order['last_name']))
                                <tr>
                                    <td width="200">{{__('Last Name')}}</td>
                                    <td>{{esc_html($order['last_name'])}}</td>
                                </tr>
                            @endif
                            <tr>
                                <td>{{__('Email')}}</td>
                                <td>{{esc_html($order['email'])}}</td>
                            </tr>
                            <tr>
                                <td>{{__('Phone')}}</td>
                                <td>{{esc_html($order['phone'])}}</td>
                            </tr>
                            <tr>
                                <td>{{__('Address')}}</td>
                                <td>{{esc_html($order['address'])}}</td>
                            </tr>
                            @if(!empty($order['city']))
                            <tr>
                                <td>{{__('City')}}</td>
                                <td>{{esc_html($order['city'])}}</td>
                            </tr>
                            @endif
                            @if(!empty($order['note']))
                            <tr>
                                <td>{{__('Note')}}</td>
                                <td>{{esc_html($order['note'])}}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="order-details">
                        <p class="h3 mb-30">{{__('Order Information')}}</p>
                        <table class="table table-hover">
                            <tr>
                                <td colspan="2"><a href="{{get_the_permalink($post['post_slug'], $order['post_type'])}}"><strong>{{get_translate($post['post_title'])}}</strong></a></td>
                            </tr>
                            <tr>
                                <td width="200">{{__('Invoice ID')}} </td>
                                <td>{{$order['sku']}}</td>
                            </tr>
                            @if($order['post_type'] == GMZ_SERVICE_CAR)
                                <tr>
                                    <td width="200">{{__('From - To')}}</td>
                                    <td>{{date(get_date_format(), $order['start_date'])}} - {{date(get_date_format(), $order['end_date'])}}</td>
                                </tr>
                                <tr>
                                    <td width="200">{{__('Period')}}</td>
                                    <td>{{sprintf(_n(__('%s day'), __('%s days'), $checkout_data['cart_data']['number_day']), $checkout_data['cart_data']['number_day'])}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('Quantity')}}</td>
                                    <td>{{$order['number']}}</td>
                                </tr>
                            @elseif($order['post_type'] == GMZ_SERVICE_APARTMENT)
                                @if($checkout_data['cart_data']['booking_type'] == 'per_day')
                                    <tr>
                                        <td width="200">{{__('From - To')}}</td>
                                        <td>{{date(get_date_format(), $order['start_date'])}} - {{date(get_date_format(), $order['end_date'])}}</td>
                                    </tr>
                                    <tr>
                                        <td width="200">{{__('Period')}}</td>
                                        <td>{{sprintf(_n(__('%s night'), __('%s nights'), $checkout_data['cart_data']['number_day']), $checkout_data['cart_data']['number_day'])}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Quantity')}}</td>
                                        <td>{{$order['number']}}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td width="200">{{__('Date')}}</td>
                                        <td>{{date(get_date_format(), $order['start_date'])}} {{date(get_time_format(), $order['start_time'])}} - {{date(get_time_format(), $order['end_time'])}}</td>
                                    </tr>
                                    <tr>
                                        <td width="200">{{__('Period')}}</td>
                                        <td>{{sprintf(_n(__('%s hour'), __('%s hours'), $checkout_data['cart_data']['number_hour']), $checkout_data['cart_data']['number_hour'])}}</td>
                                    </tr>
                                @endif
                            @elseif($order['post_type'] == GMZ_SERVICE_TOUR)
                                <tr>
                                    <td width="200">{{__('Date')}}</td>
                                    @if($post['booking_type'] == 'package')
                                    <td>{{date(get_date_format(), $order['start_date'])}} - {{date(get_date_format(), $order['end_date'])}}</td>
                                    @else
                                        <td>{{date(get_date_format(), $order['start_date'])}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>{{__('Adult')}}</td>
                                    <td>{{$checkout_data['adult']}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('Children')}}</td>
                                    <td>{{$checkout_data['children']}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('Infant')}}</td>
                                    <td>{{$checkout_data['infant']}}</td>
                                </tr>
                            @elseif($order['post_type'] == GMZ_SERVICE_SPACE)
                                @if($checkout_data['cart_data']['booking_type'] == 'per_day')
                                    <tr>
                                        <td width="200">{{__('From - To')}}</td>
                                        <td>{{date(get_date_format(), $order['start_date'])}} - {{date(get_date_format(), $order['end_date'])}}</td>
                                    </tr>
                                    <tr>
                                        <td width="200">{{__('Period')}}</td>
                                        <td>{{sprintf(_n(__('%s day'), __('%s days'), $checkout_data['cart_data']['number_day']), $checkout_data['cart_data']['number_day'])}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Quantity')}}</td>
                                        <td>{{$order['number']}}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td width="200">{{__('Date')}}</td>
                                        <td>{{date(get_date_format(), $order['start_date'])}} {{date(get_time_format(), $order['start_time'])}} - {{date(get_time_format(), $order['end_time'])}}</td>
                                    </tr>
                                    <tr>
                                        <td width="200">{{__('Period')}}</td>
                                        <td>{{sprintf(_n(__('%s hour'), __('%s hours'), $checkout_data['cart_data']['number_hour']), $checkout_data['cart_data']['number_hour'])}}</td>
                                    </tr>
                                @endif
                            @elseif($order['post_type'] == GMZ_SERVICE_BEAUTY)
                                <tr>
                                    <td width="200">{{__('Date')}}</td>
                                    <td>{{date(get_date_format(), $checkout_data['cart_data']['check_in'])}}</td>
                                </tr>
                                <tr>
                                    <td width="200">{{__('Time Slot')}}</td>
                                    <td>{{date(get_time_format(), $checkout_data['cart_data']['check_in'])}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('Agent')}}</td>
                                    <td>{{get_translate($checkout_data['cart_data']['agent_data']['post_title'])}}</td>
                                </tr>
                            @elseif($order['post_type'] == GMZ_SERVICE_HOTEL)
                                <tr>
                                    <td width="200">{{__('Check In - Check Out')}}</td>
                                    <td>{{date(get_date_format(), $order['start_date'])}} - {{date(get_date_format(), $order['end_date'])}}</td>
                                </tr>
                                <tr>
                                    <td width="200">{{__('Number of Days')}}</td>
                                    <td>{{ $checkout_data['cart_data']['number_day']}}</td>
                                </tr>
                                <tr>
                                    <td width="200">{{__('Total Rooms')}}</td>
                                    <td>{{ $checkout_data['cart_data']['number']}}</td>
                                </tr>
                                <tr>
                                    <td width="200">{{__('Number of Adult')}}</td>
                                    <td>{{ $checkout_data['cart_data']['adult']}}</td>
                                </tr>
                                @if(!empty($checkout_data['cart_data']['children']))
                                <tr>
                                    <td width="200">{{__('Number of Children')}}</td>
                                    <td>{{ $checkout_data['cart_data']['children']}}</td>
                                </tr>
                                @endif
                                @if(!empty($checkout_data['cart_data']['rooms']))
                                    <tr>
                                        <td width="200">{{__('Room Details')}}</td>
                                        <td>
                                            @foreach($checkout_data['cart_data']['rooms'] as $k => $v)
                                                @php
                                                    $room_object = get_post($k, GMZ_SERVICE_ROOM);
                                                @endphp

                                                    <div class="label"><b>{{get_translate($room_object['post_title'])}}</b></div>
                                                    <div class="value">
                                                        {{sprintf(_n(__('%s room'), __('%s rooms'), $v['number']), $v['number'])}} x {{convert_price($v['price'])}} = {{convert_price($v['number'] * $v['price'])}}

                                                    </div>

                                            @endforeach
                                        </td>
                                    </tr>
                                @endif

                                @php
                                    $extras = $checkout_data['cart_data']['extras'];
                                @endphp
                                @if(!empty($extras) && $extras != '[]')
                                    <tr>
                                        <td width="200">
                                            {{__('Extra Services')}}
                                        </td>
                                        <td>
                                            @foreach($extras as $item)
                                                <div>
                                            {{get_translate($item['title'])}}: {{convert_price($item['price'])}}
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif

                            @endif
                            @if(!empty($checkout_data['tax']['included']) && !empty($checkout_data['tax']['percent']))
                                <tr>
                                    <td>
                                        {{__('Tax')}}
                                        @if($checkout_data['tax']['included'] == 'on')
                                            <small>{{__('(included)')}}</small>
                                        @endif
                                    </td>
                                    <td>{{$checkout_data['tax']['percent']}}%</td>
                                </tr>
                            @endif
                            <tr>
                                <td>{{__('Total amount')}}</td>
                                <td>{{convert_price($order['total'])}}</td>
                            </tr>
                            <tr>
                                <td>{{__('Status')}}</td>
                                <td>{{ucfirst($order['status'])}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        @else
            &thinsp;
        @endif
        <div class="pt-60"></div>

    </div>

@stop

