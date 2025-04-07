<div class="hotel-star">
    @php
        echo hotel_star($post['hotel_star'])
    @endphp
</div>
<h1 class="post-title">
    @php echo add_wishlist_box($post['id'], GMZ_SERVICE_HOTEL); @endphp
    {{get_translate($post['post_title'])}}
    @if($post['is_featured'] == 'on')
        <span class="is-featured">{{__('Featured')}}</span>
    @endif
</h1>
<p class="location">
    <i class="fal fa-map-marker-alt"></i> {{get_translate($post['location_address'])}}
</p>
@if(!empty($post['rating']))
    @php
        $review_number = get_comment_number($post['id'], $post['post_type']);
    @endphp
    <div class="count-reviews">
        <span>{{$post['rating']}}<small>/5</small><i class="fa fa-star"></i></span> {{sprintf(_n(__('from %s review'), __('from %s reviews'), $review_number), $review_number)}}
    </div>
@endif