@php
    $cartData = $checkoutData['cart_data'];
    if (empty($checkoutData['post_id']) && !empty($checkoutData['service'])){
       $postIds = explode(',', $checkoutData['service']);
    }else{
       $postIds = [$checkoutData['post_id']];
    }

@endphp
<tr>
    <td class="label">{{__('Service Name')}}</td>
    <td class="val">
        @foreach($postIds as $id)
            @php
               $post_object = get_post($id, $checkoutData['post_type']);
               $link = get_the_permalink($post_object['post_slug'], $order['post_type']);
               $title = get_translate($post_object['post_title']);
               $address = get_translate($post_object['location_address']);
            @endphp
            <p><a href="{{$link}}">{{$title}}</a></p>
            <span>{{$address}}</span>
        @endforeach
    </td>
</tr>
<tr>
    <td class="label">{{__('Date')}}</td>
    <td class="val">{{date(get_date_format(), $cartData['check_in'])}}</td>
</tr>
<tr>
    <td class="label">{{__('Time Slot')}}</td>
    <td class="val">{{date(get_time_format(), $cartData['check_in'])}}</td>
</tr>
<tr>
    <td class="label">{{__('Agent')}}</td>
    <td class="val">{{get_translate($cartData['agent_data']['post_title'])}}</td>
</tr>