@if(empty($postData))
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
@else
    @foreach($postData as $p)
        <div class="cart-info__heading d-flex">
            @if(!empty($p['thumbnail']))
                <div class="thumbnail">
                    <a href="{{$p['link']}}">
                        <img src="{{$p['thumbnail']}}" alt="{{$p['title']}}"/>
                    </a>
                </div>
            @endif
            <div class="info">
                <h3 class="title">
                    <a href="{{$p['link']}}">
                        {{$p['title']}}
                    </a>
                </h3>
                @if(!empty($p['address']))
                    <p class="location">{{$p['address']}}</p>
                @endif
            </div>
        </div>
    @endforeach
@endif

<ul class="cart-info__meta">
    <li>
        <span class="label">{{__('Date')}}</span>
        <span class="value">{{date(get_date_format(), $cart_data['check_in'])}}</span>
    </li>
    <li>
        <span class="label">{{__('Time Slot')}}</span>
        <span class="value">{{date(get_time_format(), $cart_data['check_in'])}}</span>
    </li>
    <li>
        <span class="label">{{__('Agent')}}</span>
        <span class="value">{{get_translate($checkout_data['cart_data']['agent_data']['post_title'])}}</span>
    </li>
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