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
        <span class="label">{{__('From')}}</span>
        <span class="value">{{date(get_date_format(), $cart_data['check_in'])}}</span>
    </li>
    <li>
        <span class="label">{{__('To')}}</span>
        <span class="value">{{date(get_date_format(), $cart_data['check_out'])}}</span>
    </li>
    <li>
        <span class="label">{{__('Number of Days')}}</span>
        <span class="value">{{$checkout_data['cart_data']['number_day']}}</span>
    </li>
    <li>
        <span class="label">{{__('Quantity')}}</span>
        <span class="value">{{$cart_data['number']}}</span>
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
@php
    $equipments = $cart_data['equipment_data'];
    $insurances = $cart_data['insurance_data'];
@endphp
@if(!empty($equipments))
    <div class="cart-info__equipment">
        <div class="__label">
            {{__('Equipments')}}
        </div>
        <table>
            <tr>
                <th>{{__('Name')}}</th>
                <th>{{__('Price')}}</th>
            </tr>
            @foreach($equipments as $item)
                <tr>
                    <td>{{get_translate($item['term_title'])}}</td>
                    <td>{{convert_price($item['custom_price'])}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endif

@if(!empty($insurances))
    <div class="cart-info__insurance">
        <div class="__label">
            {{__('Insurance Plan')}}
        </div>
        <table>
            <tr>
                <th>{{__('Name')}}</th>
                <th>{{__('Price')}}</th>
                <th>{{__('Fixed')}}</th>
            </tr>
            @foreach($insurances as $item)
                <tr>
                    <td>{{get_translate($item['title'])}}</td>
                    <td>{{convert_price($item['price'])}}</td>
                    <td>
                        @if($item['fixed'] == 'on')
                            {{__('Yes')}}
                        @else
                            {{__('No')}}
                        @endif
                    </td>
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
        @if(!empty($equipments))
            <li>
                <span class="label">{{__('Equipment Price')}}</span>
                <span class="value">{{convert_price($checkout_data['equipment_price'])}}</span>
            </li>
        @endif
        @if(!empty($insurances))
            <li>
                <span class="label">{{__('Insurance Price')}}</span>
                <span class="value">{{convert_price($checkout_data['insurance_price'])}}</span>
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