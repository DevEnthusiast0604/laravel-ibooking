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
    <td class="label">{{__('Date')}}</td>
    <td class="val">{{date(get_date_format(), $cartData['check_in'])}}</td>
</tr>
@if(!empty($checkoutData['adult']))
    <tr>
        <td class="label">{{__('Number of Adult')}}</td>
        <td class="val">{{$checkoutData['adult']}}</td>
    </tr>
@endif
@if(!empty($checkoutData['children']))
    <tr>
        <td class="label">{{__('Number of Children')}}</td>
        <td class="val">{{$checkoutData['children']}}</td>
    </tr>
@endif
@if(!empty($checkoutData['infant']))
    <tr>
        <td class="label">{{__('Number of Infant')}}</td>
        <td class="val">{{$checkoutData['infant']}}</td>
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