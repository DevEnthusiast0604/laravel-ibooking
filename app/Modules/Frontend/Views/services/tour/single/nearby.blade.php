@php
    $list_tours = get_posts([
        'post_type' => GMZ_SERVICE_TOUR,
        'posts_per_page' => 3,
        'post_not_in' => [$post['id']],
        'nearby' => [
            'lat' => floatval($post['location_lat']),
            'lng' => floatval($post['location_lng']),
            'distance' => 50
        ],
    ]);
enqueue_scripts('match-height');
$search_url = url('tour-search');
@endphp
@if(!$list_tours->isEmpty())
    <section class="list-tour list-tour--grid py-40 bg-gray-100 mb-0 nearby">
        <div class="container">
            <h2 class="section-title mb-20">{{__('Tours Near By')}}</h2>
            <div class="row">
                @foreach($list_tours as $item)
                    @php
                        $img = get_attachment_url($item->thumbnail_id, [360, 240]);
                        $title = get_translate($item->post_title);
                        $type = get_term('id', $item->tour_type);
                    @endphp
                    <div class="col-md-4">
                        @include('Frontend::services.tour.items.grid-item')
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif