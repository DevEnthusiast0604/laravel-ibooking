@foreach($data as $val)
    <div class="beauty-item beauty-item--list">
        <div class="row">
            <div class="col-4">
                <div class="beauty-item__thumbnail">
                    @php echo add_wishlist_box($val['id'], GMZ_SERVICE_BEAUTY) @endphp
                    <a href="{{get_beauty_permalink($val['post_slug'])}}">
                        <img src="{{get_attachment_url($val['thumbnail_id'], [360, 240])}}" alt="{{get_attachment_alt($val['thumbnail_id'])}}" class="loaded">
                    </a>
                    @if($val['is_featured'] == 'on')
                        <p class="beauty-item__label">{{__('Feature')}}</p>
                    @endif
                </div>
            </div>
            <div class="col-8">
                <div class="beauty-item__details">
                    <div class="star-rating"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <h3 class="beauty-item__title"><a href="{{get_beauty_permalink($val['post_slug'])}}">{{get_translate($val['post_title'])}}</a></h3>
                    <p class="beauty-item__location"><i class="fal fa-map-marker-alt mr-2"></i>{{get_translate($val['location_address'])}}</p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <div class="beauty-item__price">
                            <span class="_retail">{{convert_price($val['base_price'])}}</span><span class="_unit">{{__('times')}}</span>
                        </div>
                        <a class="btn btn-primary" href="{{get_beauty_permalink($val['post_slug'])}}">{{__('View Detail')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach