@php
    $img = get_attachment_url($item->thumbnail_id, [360, 240]);
    $title = get_translate($item->post_title);
    $type = get_term('id', $item->space_type);
    $location = get_translate($item->location_address);
    $search_url = url('space-search');
@endphp
<div class="space-item space-item--grid" data-plugin="matchHeight">
    <div class="space-item__thumbnail">
        @php echo add_wishlist_box($item->id, GMZ_SERVICE_SPACE); @endphp
        <a href="{{get_space_permalink($item->post_slug)}}">
            <img src="{{$img}}" alt="{{$title}}">
        </a>
        @if(!empty($type))
            @php
                $search_url = add_query_arg(['space_type' => $type->id], $search_url);
            @endphp
            <a class="space-item__type" href="{{$search_url}}">
                {{get_translate($type->term_title)}}
            </a>
        @endif
    </div>
    <div class="space-item__details">
        @if($item->is_featured == 'on')
            <span class="space-item__label">{{__('Featured')}}</span>
        @endif
        <h3 class="space-item__title"><a href="{{get_space_permalink($item->post_slug)}}">{{$title}}</a></h3>
        <div class="space-item__meta">
            <div class="i-meta" data-toggle="tooltip" title="{{__('Guests')}}">
                <span class="i-meta__icon">{!! get_icon('icon_system_passenger') !!}</span>
                <span class="i-meta__figure">{{$item->number_of_guest}}</span>
            </div>
            <div class="i-meta" data-toggle="tooltip" title="{{__('Bedroom')}}">
                <span class="i-meta__icon">{!! get_icon('icon_system_bedroom') !!}</span>
                <span class="i-meta__figure">{{$item->number_of_bedroom}}</span>
            </div>
            <div class="i-meta" data-toggle="tooltip" title="{{__('Bathroom')}}">
                <span class="i-meta__icon">{!! get_icon('icon_system_bathroom') !!}</span>
                <span class="i-meta__figure">{{$item->number_of_bathroom}}</span>
            </div>
            <div class="i-meta" data-toggle="tooltip" title="{{__('Size')}}">
                <span class="i-meta__icon">{!! get_icon('icon_system_size') !!}</span>
                <span class="i-meta__figure">{{get_translate($item->size)}} {{get_option('unit_of_measure', 'm2')}}</span>
            </div>
        </div>
        @if(!empty($location))
            <p class="space-item__location"><i class="fas fa-map-marker-alt mr-2"></i>{{$location}}</p>
        @endif
        <div class="d-flex justify-content-between align-items-center">
            <div class="space-item__price">
                <span class="_retail">{{convert_price($item->base_price)}}</span><span class="_unit">{{get_booking_type($item->booking_type)}}</span>
            </div>
            <a class="btn btn-primary space-item__view-detail" href="{{get_space_permalink($item->post_slug)}}">{{__('View Detail ')}}</a>
        </div>
    </div>
</div>