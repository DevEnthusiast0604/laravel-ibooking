@php
    $checkout_data = json_decode($data['checkout_data'], true);
    $post_type = $data['post_type'];
    $postData = [];
    if (empty($checkout_data['post_id']) && !empty($checkout_data['service'])){
        //only beauty service with multiple service
        $postIds = explode(',', $checkout_data['service']);
        foreach ($postIds as $postId){
            $post_object = get_post($postId, $post_type);
            $postData[] = [
                'thumbnail' => get_attachment_url($post_object['thumbnail_id'], [70, 70]),
                'link' => get_the_permalink($post_object['post_slug'], $post_type),
                'title' => get_translate($post_object['post_title']),
                'address' => get_translate($post_object['location_address'])
            ];
        }
    }else{
        $post_object = get_post($checkout_data['post_id'], $post_type);
        $thumbnail = get_attachment_url($post_object['thumbnail_id'], [70, 70]);
        $link = get_the_permalink($post_object['post_slug'], $post_type);
        $title = get_translate($post_object['post_title']);
        $address = get_translate($post_object['location_address']);
    }
    $cart_data = $checkout_data['cart_data'];
    $log = get_processing_log($data['change_log']);
@endphp
<div class="order-detail">
    <div class="order-detail__content">
        <ul class="nav nav-tabs  mb-3 mt-3" id="orderDetailTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="detail-tab" data-toggle="tab" href="#detail" role="tab"
                   aria-controls="detail" aria-selected="true">{{__('Detail')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="customer-tab" data-toggle="tab" href="#customer" role="tab"
                   aria-controls="customer" aria-selected="true">{{__('Customer')}}</a>
            </li>
            @if($log && is_admin())
                <li class="nav-item">
                    <a class="nav-link" id="log-tab" data-toggle="tab" href="#change-log" role="tab"
                       aria-controls="change-log" aria-selected="true">{{__('Change log')}}</a>
                </li>
            @endif
        </ul>
        <div class="tab-content" id="orderDetailTabContent">
            <div class="tab-pane fade show active" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                <div class="cart-info">
                    @include('Backend::services.' . $post_type . '.cart-info')
                </div>
            </div>
            <div class="tab-pane fade" id="customer" role="tabpanel" aria-labelledby="customer-tab">
                <div class="cart-info">
                    <ul class="cart-info__meta">
                        <li>
                            <span class="label">{{__('First Name')}}</span>
                            <span class="value">{{$data['first_name']}}</span>
                        </li>
                        @if(!empty($data['last_name']))
                            <li>
                                <span class="label">{{__('Last Name')}}</span>
                                <span class="value">{{$data['last_name']}}</span>
                            </li>
                        @endif
                        @if(!empty($data['email']))
                            <li>
                                <span class="label">{{__('Email')}}</span>
                                <span class="value">{{$data['email']}}</span>
                            </li>
                        @endif
                        @if(!empty($data['phone']))
                            <li>
                                <span class="label">{{__('Phone')}}</span>
                                <span class="value">{{$data['phone']}}</span>
                            </li>
                        @endif
                        @if(!empty($data['address']))
                            <li>
                                <span class="label">{{__('Address')}}</span>
                                <span class="value">{{$data['address']}}</span>
                            </li>
                        @endif
                        @if(!empty($data['city']))
                            <li>
                                <span class="label">{{__('City')}}</span>
                                <span class="value">{{$data['city']}}</span>
                            </li>
                        @endif
                        @if(!empty($data['country']))
                            <li>
                                <span class="label">{{__('Country')}}</span>
                                <span class="value">{{list_countries($data['country'])}}</span>
                            </li>
                        @endif
                        @if(!empty($data['postcode']))
                            <li>
                                <span class="label">{{__('Postcode')}}</span>
                                <span class="value">{{$data['postcode']}}</span>
                            </li>
                        @endif
                        @if(!empty($data['note']))
                            <li class="d-block">
                                <span class="label d-block mb-2">{{__('Note')}}</span>
                                <span class="value">{{$data['note']}}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            @if($log && is_admin())
                <div class="tab-pane fade" id="change-log" role="tabpanel" aria-labelledby="log-tab">
                    <table class="table table-change-log">
                        <tr>
                            <th>{{__('By')}}</th>
                            <th>{{__('Action')}}</th>
                            <th>{{__('At')}}</th>
                        </tr>
                        @foreach(array_reverse($log) as $v)
                            <tr>
                                @if(is_int($v['user']))
                                    <td>{{get_user_name($v['user'])}}</td>
                                @else
                                    <td>{{__(ucwords($v['user']))}}</td>
                                @endif
                                <td>{{$v['action']}}</td>
                                <td>{{date(get_date_format(true), $v['create'])}}</td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            @endif
        </div>
    </div>
</div>