@php
    $link = get_the_permalink($post['post_slug'], $order['post_type']);
    $title = get_translate($post['post_title']);
    $address = get_translate($post['location_address']);
    $cartData = $checkoutData['cart_data'];
    $equipments = $cartData['equipment_data'];
    $insurances = $cartData['insurance_data'];
@endphp
<tr>
    <td class="label">{{__('Service Name')}}</td>
    <td class="val">
        <p><a href="{{$link}}">{{$title}}</a></p>
        <span>{{$address}}</span>
    </td>
</tr>
<tr>
    <td class="label">{{__('From')}}</td>
    <td class="val">{{date(get_date_format(), $cartData['check_in'])}}</td>
</tr>
<tr>
    <td class="label">{{__('To')}}</td>
    <td class="val">{{date(get_date_format(), $cartData['check_out'])}}</td>
</tr>
<tr>
    <td class="label">{{__('Number of Days')}}</td>
    <td class="val">{{$cartData['number_day']}}</td>
</tr>
<tr>
    <td class="label">{{__('Quantity')}}</td>
    <td class="val">{{$cartData['number']}}</td>
</tr>
@if(!empty($equipments))
    <tr>
        <td class="label">{{__('Equipments')}}</td>
        <td class="val">
            @foreach($equipments as $item)
                <div style="margin-bottom: 7px;">
                <small><i>{{get_translate($item['term_title'])}}: {{convert_price($item['custom_price'])}}</i></small>
                </div>
            @endforeach
        </td>
    </tr>
@endif
@if(!empty($insurances))
    <tr>
        <td class="label">{{__('Insurance Plan')}}</td>
        <td class="val">
            @foreach($insurances as $item)
                <div style="margin-bottom: 7px;">
                    <small>
                        <i>
                            {{get_translate($item['title'])}}: {{convert_price($item['price'])}}
                            @if($item['fixed'] == 'on')
                                ({{__('Fixed')}})
                            @endif
                        </i>
                    </small>
                </div>
            @endforeach
        </td>
    </tr>
@endif