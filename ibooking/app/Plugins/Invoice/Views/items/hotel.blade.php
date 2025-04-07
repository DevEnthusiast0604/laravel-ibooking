@php
    $link = get_the_permalink($post['post_slug'], $order['post_type']);
    $title = get_translate($post['post_title']);
    $address = get_translate($post['location_address']);
    $cartData = $checkoutData['cart_data'];
@endphp
<tr>
    <td class="label">{{__('Service Name')}}</td>
    <td class="val">
        <p><a href="{{$link}}">{{$title}}</a></p>
        <span>{{$address}}</span>
    </td>
</tr>
<tr>
    <td class="label">{{__('Check In')}}</td>
    <td class="val">{{date(get_date_format(), $cartData['check_in'])}}</td>
</tr>
<tr>
    <td class="label">{{__('Check Out')}}</td>
    <td class="val">{{date(get_date_format(), $cartData['check_out'])}}</td>
</tr>
<tr>
    <td class="label">{{__('Number of Days')}}</td>
    <td class="val">{{$cartData['number_day']}}</td>
</tr>
<tr>
    <td class="label">{{__('Total rooms')}}</td>
    <td class="val">{{$cartData['number']}}</td>
</tr>
@if(!empty($cartData['adult']))
    <tr>
        <td class="label">{{__('Number of Adult')}}</td>
        <td class="val">{{$cartData['adult']}}</td>
    </tr>
@endif
@if(!empty($cart_data['children']))
    <tr>
        <td class="label">{{__('Number of children')}}</td>
        <td class="val">{{$cartData['children']}}</td>
    </tr>
@endif
@if(!empty($cartData['rooms']))
    <tr>
        <td class="label">{{__('Room Details')}}</td>
        <td class="val">
            @foreach($cartData['rooms'] as $k => $v)
                @php
                    $roomObject = get_post($k, GMZ_SERVICE_ROOM);
                @endphp
                <div class="mb-1 room-detail">
                    <div>{{get_translate($roomObject['post_title'])}}</div>
                    <div>
                        <small> {{sprintf(_n(__('%s room'), __('%s rooms'), $v['number']), $v['number'])}} x {{convert_price($v['price'])}} =
                            {{convert_price($v['number'] * $v['price'])}}
                        </small>
                    </div>
                </div>
            @endforeach
        </td>
    </tr>
@endif
@if(!empty($extras) && $extras != '[]')
    <tr>
        <td class="label">{{__('Extra Services')}}</td>
        <td class="val">
            @foreach($extras as $item)
                <div style="margin-bottom: 7px;">
                <small><i>{{get_translate($item['title'])}}: {{convert_price($item['price'])}}</i></small>
                </div>
            @endforeach
        </td>
    </tr>
@endif