@php
    $img = get_attachment_url($item->thumbnail_id, [360, 240]);
    $title = get_translate($item->post_title);
    $type = get_term('id', $item->property_type);
    $location = get_translate($item->location_address);
    $search_url = url('hotel-search');
@endphp
<div class="hotel-item hotel-item--grid" data-plugin="matchHeight">
    <div class="hotel-item__thumbnail">

        @php echo add_wishlist_box($item->id, GMZ_SERVICE_HOTEL); @endphp

        <a href="{{get_hotel_permalink($item->post_slug)}}">
            <img src="{{$img}}" alt="{{$title}}">
        </a>
        @if(!empty($type))
            @php
                $search_url = add_query_arg(['property_type' => $type->id], $search_url);
            @endphp
            <a class="hotel-item__type" href="{{$search_url}}">
                {{get_translate($type->term_title)}}
            </a>
        @endif
    </div>
    <div class="hotel-item__details">
        @if($item->is_featured == 'on')
            <span class="hotel-item__label">{{__('Featured')}}</span>
        @endif
            <div class="hotel-item__rating">
                @php echo hotel_star($item->hotel_star) @endphp
            </div>
        <h3 class="hotel-item__title"><a href="{{get_hotel_permalink($item->post_slug)}}">{{$title}}</a></h3>
        @if(!empty($location))
            <p class="hotel-item__location"><i class="fas fa-map-marker-alt mr-2"></i>{{get_translate($item->location_address)}}</p>
        @endif
        <div class="d-flex justify-content-between align-items-center">
            <div class="hotel-item__price">
                <span class="_retail">{{convert_price($item->base_price)}}</span><span class="_unit">{{__('night')}}</span>
            </div>
            <a class="btn btn-primary hotel-item__view-detail" href="{{get_hotel_permalink($item->post_slug)}}">{{__('View Detail ')}}</a>
        </div>
    </div>
</div>