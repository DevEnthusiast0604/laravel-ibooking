@php
    $img = get_attachment_url($item->thumbnail_id, [360, 240]);
    $title = get_translate($item->post_title);
    $location = get_translate($item->location_address);
@endphp
<div class="tour-item tour-item--list">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
            <div class="tour-item__thumbnail">
                @php echo add_wishlist_box($item->id, GMZ_SERVICE_TOUR); @endphp
                <a href="{{get_tour_permalink($item->post_slug)}}">
                    <img src="{{$img}}" alt="{{$title}}">
                </a>
                @if($item->is_featured == 'on')
                    <span class="tour-item__label">{{__('Featured')}}</span>
                @endif
                @action('gmz_tour_single_after_thumbnail', $item)
            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-12">
            <div class="tour-item__details">
                <h3 class="tour-item__title"><a href="{{get_tour_permalink($item->post_slug)}}">{{$title}}</a></h3>
                @if(!empty($location))
                    <p class="tour-item__location"><i class="fas fa-map-marker-alt mr-2"></i>{{$location}}</p>
                @endif
                <div class="tour-item__meta">
                    <div class="meta-item duration">
                        <i class="fal fa-calendar-alt"></i>
                        <div class="duration-info">
                            <span class="label">{{__('Duration')}}</span>
                            <span class="value">{{get_translate($item->duration)}}</span>
                        </div>
                    </div>
                    <div class="meta-item group-size">
                        <i class="fal fa-users"></i>
                        <div class="group-size-info">
                            <span class="label">{{__('Group Size')}}</span>
                            <span class="value">{{sprintf(__('%s people'), $item->group_size)}}</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="tour-item__price">
                        <span class="_retail">{{convert_price($item['adult_price'])}}</span>
                    </div>
                    <a class="btn btn-primary tour-item__view-detail" href="{{get_tour_permalink($item->post_slug)}}">{{__('View Detail ')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>