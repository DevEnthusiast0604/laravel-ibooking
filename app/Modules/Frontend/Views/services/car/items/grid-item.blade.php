@php
    $img = get_attachment_url($item->thumbnail_id, [360, 240]);
    $title = get_translate($item->post_title);
    $type = get_term('id', $item->car_type);
    $location = get_translate($item->location_address);
    $search_url = url('car-search');
@endphp
<div class="car-item car-item--grid" data-plugin="matchHeight">
    <div class="car-item__thumbnail">
        @php echo add_wishlist_box($item->id, GMZ_SERVICE_CAR); @endphp
        <a href="{{get_car_permalink($item->post_slug)}}">
            <img src="{{$img}}" alt="{{$title}}">
        </a>
        @if(!empty($type))
            @php
                $search_url = add_query_arg(['car_type' => $type->id], $search_url);
            @endphp
            <a class="car-item__type" href="{{$search_url}}">
                {{get_translate($type->term_title)}}
            </a>
        @endif
    </div>
    <div class="car-item__details">
        @if($item->is_featured == 'on')
        <span class="car-item__label">{{__('Featured')}}</span>
        @endif
        <h3 class="car-item__title"><a href="{{get_car_permalink($item->post_slug)}}">{{$title}}</a></h3>
        <div class="car-item__meta">
            <div class="i-meta" data-toggle="tooltip" title="{{__('Passenger')}}">
                <span class="i-meta__icon">{!! get_icon('icon_system_passenger') !!}</span>
                <span class="i-meta__figure">{{$item->passenger}}</span>
            </div>
            <div class="i-meta" data-toggle="tooltip" title="{{__('Doors')}}">
                <span class="i-meta__icon">{!! get_icon('icon_system_door') !!}</span>
                <span class="i-meta__figure">{{$item->door}}</span>
            </div>
            <div class="i-meta" data-toggle="tooltip" title="{{__('Baggage')}}">
                <span class="i-meta__icon">{!! get_icon('icon_system_baggage') !!}</span>
                <span class="i-meta__figure">{{$item->baggage}}</span>
            </div>
            <div class="i-meta" data-toggle="tooltip" title="{{__('Gear Shift')}}">
                <span class="i-meta__icon">{!! get_icon('icon_system_gear_shift') !!}</span>
                <span class="i-meta__figure">{{get_gear_shift($item->gear_shift)}}</span>
            </div>
        </div>
            @if(!empty($location))
        <p class="car-item__location"><i class="fas fa-map-marker-alt mr-2"></i>{{get_translate($item->location_address)}}</p>
            @endif
        <div class="d-flex justify-content-between align-items-center">
            <div class="car-item__price">
                <span class="_retail">{{convert_price($item->base_price)}}</span><span class="_unit">{{__('per day')}}</span>
            </div>
            <a class="btn btn-primary car-item__view-detail" href="{{get_car_permalink($item->post_slug)}}">{{__('View Detail ')}}</a>
        </div>
    </div>
</div>