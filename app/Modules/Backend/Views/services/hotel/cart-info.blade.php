<div class="cart-info__heading d-flex">
    @if(!empty($thumbnail))
        <div class="thumbnail">
            <a href="{{$link}}">
                <img src="{{$thumbnail}}" alt="{{$title}}"/>
            </a>
        </div>
    @endif
    <div class="info">
        <h3 class="title">
            <a href="{{$link}}">
                {{$title}}
            </a>
        </h3>
        @if(!empty($address))
            <p class="location">{{$address}}</p>
        @endif
    </div>
</div>
<ul class="cart-info__meta">
    <li>
        <span class="label">{{__('Check In')}}</span>
        <span class="value">{{date(get_date_format(), $cart_data['check_in'])}}</span>
    </li>
    <li>
        <span class="label">{{__('Check Out')}}</span>
        <span class="value">{{date(get_date_format(), $cart_data['check_out'])}}</span>
    </li>
    <li>
        <span class="label">{{__('Number of Days')}}</span>
        <span class="value">{{$cart_data['number_day']}}</span>
    </li>
    <li>
        <span class="label">{{__('Total rooms')}}</span>
        <span class="value">{{$cart_data['number']}}</span>
    </li>
    @if(!empty($cart_data['adult']))
        <li>
            <span class="label">{{__('Number of Adult')}}</span>
            <span class="value">{{$cart_data['adult']}}</span>
        </li>
    @endif
    @if(!empty($cart_data['children']))
        <li>
            <span class="label">{{__('Number of children')}}</span>
            <span class="value">{{$cart_data['children']}}</span>
        </li>
    @endif
    @if(!empty($cart_data['rooms']))
        <li>
            <span class="label">{{__('Room Details')}}</span>
            <div class="value">
            @foreach($cart_data['rooms'] as $k => $v)
                @php
                    $room_object = get_post($k, GMZ_SERVICE_ROOM);
                @endphp
                <div class="mb-1">
                    <div>{{get_translate($room_object['post_title'])}}</div>
                    <div>
                        <small> {{sprintf(_n(__('%s room'), __('%s rooms'), $v['number']), $v['number'])}} x {{convert_price($v['price'])}} =
                               {{convert_price($v['number'] * $v['price'])}}
                        </small>
                    </div>
                </div>
            @endforeach
            </div>
        </li>
    @endif
    @php
        $gateway = Gateway::inst()->getGateway($data['payment_type']);
    @endphp
    <li>
        <span class="label">{{__('Payment Method')}}</span>
        <span class="value">{{$gateway->getName()}}</span>
    </li>
    <li>
        <span class="label">{{__('Payment Status')}}</span>
        <span class="value">{!! the_paid($data['payment_status']) !!}</span>
    </li>
</ul>
@php
    $extras = $cart_data['extras'];
@endphp
@if(!empty($extras) && $extras != '[]')
    <div class="cart-info__equipment">
        <div class="__label">
            {{__('Extra Services')}}
        </div>
        <table>
            <tr>
                <th>{{__('Name')}}</th>
                <th>{{__('Price')}}</th>
            </tr>
            @foreach($extras as $item)
                <tr>
                    <td>{{get_translate($item['title'])}}</td>
                    <td>{{convert_price($item['price'])}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endif

<div class="cart-info__price">
    <ul>
        <li>
            <span class="label">{{__('Base Price')}}</span>
            <span class="value">{{convert_price($checkout_data['base_price'])}}</span>
        </li>
        @if(!empty($extras) && $extras != '[]')
            <li>
                <span class="label">{{__('Extra Price')}}</span>
                <span class="value">{{convert_price($checkout_data['extra_price'])}}</span>
            </li>
        @endif
        @if(!empty($checkout_data['coupon']))
            <li>
                <span class="label">{{__('Coupon')}} ({{$checkout_data['coupon']}})</span>
                <span class="value">-{{$checkout_data['coupon_percent']}}%</span>
            </li>
        @endif
        <li>
            <span class="label">{{__('Sub Total')}}</span>
            <span class="value">{{convert_price($checkout_data['sub_total'])}}</span>
        </li>
        @if(!empty($checkout_data['tax']['included']) && !empty($checkout_data['tax']['percent']))
            <li>
                                    <span class="label">
                                        {{__('Tax')}}
                                        @if($checkout_data['tax']['included'] == 'on')
                                            <small>{{__('(included)')}}</small>
                                        @endif
                                    </span>
                <span class="value">{{$checkout_data['tax']['percent']}}%</span>
            </li>
        @endif
        <li class="total">
            <span class="label">{{__('Total Amount')}}</span>
            <span class="value">{{convert_price($checkout_data['total'])}}</span>
        </li>
    </ul>
</div>