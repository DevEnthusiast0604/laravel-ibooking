@php
    $img = get_attachment_url($item->thumbnail_id, [360, 240]);
    $title = get_translate($item->post_title);
    $type = get_term('id', $item->service);
    $location = get_translate($item->location_address);
    $search_url = url('beauty-search');
@endphp
<div class="beauty-item beauty-item--grid" data-plugin="matchHeight">
    <div class="beauty-item__thumbnail">
        @php echo add_wishlist_box($item->id, GMZ_SERVICE_BEAUTY); @endphp
        <a href="{{get_beauty_permalink($item->post_slug)}}">
            <img src="{{$img}}" alt="{{$title}}">
        </a>
        @if(!empty($type))
            @php
                $search_url = add_query_arg(['service' => $type->id], $search_url);
            @endphp
            <a class="beauty-item__type" href="{{$search_url}}">
                {{get_translate($type->term_title)}}
            </a>
        @endif
    </div>
    <div class="beauty-item__details">
        @if($item->is_featured == 'on')
            <span class="beauty-item__label">{{__('Featured')}}</span>
        @endif
        <h3 class="beauty-item__title"><a href="{{get_beauty_permalink($item->post_slug)}}">{{$title}}</a></h3>
        @if(!empty($location))
            <p class="beauty-item__location"><i class="fas fa-map-marker-alt mr-2"></i>{{get_translate($item->location_address)}}</p>
        @endif
        <div class="d-flex justify-content-between align-items-center">
            <div class="beauty-item__price">
                <span class="_retail">{{convert_price($item->base_price)}}</span>
            </div>
            <a class="btn btn-primary beauty-item__view-detail" href="{{get_beauty_permalink($item->post_slug)}}">{{__('View Detail')}}</a>
        </div>
    </div>
</div>