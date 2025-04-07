@php
    $img = get_attachment_url($item->thumbnail_id, [360, 240]);
    $title = get_translate($item->post_title);
    $type = get_term('id', $item->apartment_type);
    $location = get_translate($item->location_address);
    $search_url = url('apartment-search');
@endphp
<div class="apartment-item apartment-item--list" data-id="{{$item->id}}" data-lat="{{$item->location_lat}}" data-lng="{{$item->location_lng}}">
    <div class="row">
        <div class="col-4">
            <div class="apartment-item__thumbnail">
                @php echo add_wishlist_box($item->id, GMZ_SERVICE_APARTMENT); @endphp
                <a href="{{get_apartment_permalink($item->post_slug)}}">
                    <img src="{{$img}}" alt="{{$title}}">
                </a>
                @if($item->is_featured == 'on')
                    <p class="apartment-item__label">{{__('Featured')}}</p>
                @endif
                @if(!empty($type))
                    @php
                        $search_url = add_query_arg(['apartment_type' => $type->id], $search_url);
                    @endphp
                    <a class="apartment-item__type" href="{{$search_url}}">
                        {{get_translate($type->term_title)}}
                    </a>
                @endif
            </div>
        </div>
        <div class="col-8">
            <div class="apartment-item__details">
                <h3 class="apartment-item__title"><a href="{{get_apartment_permalink($item->post_slug)}}">{{$title}}</a></h3>
                @if(!empty($location))
                <p class="apartment-item__location"><i class="fal fa-map-marker-alt mr-2"></i>{{get_translate($item->location_address)}}</p>
                @endif
                <div class="apartment-item__meta">
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
                <div class="d-flex justify-content-between align-items-center mt-auto">
                    <div class="apartment-item__price">
                        <span class="_retail">{{convert_price($item->base_price)}}</span><span class="_unit">{{__('per night')}}</span>
                    </div>
                    <a class="btn btn-primary" href="{{get_apartment_permalink($item->post_slug)}}">{{__('View Detail ')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>