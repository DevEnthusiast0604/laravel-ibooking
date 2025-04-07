@php
    $nearby_locations = [
        [
            'title' => __('What\'s Nearby'),
            'data' => maybe_unserialize($post['nearby_common'])
        ],
        [
            'title' => __('Closest Education'),
            'data' => maybe_unserialize($post['nearby_education'])
        ],
        [
            'title' => __('Closest Health'),
            'data' => maybe_unserialize($post['nearby_health'])
        ],
        [
            'title' => __('Top attractions'),
            'data' => maybe_unserialize($post['nearby_top_attractions'])
        ],
        [
            'title' => __('Restaurants & Cafes'),
            'data' => maybe_unserialize($post['nearby_restaurants_cafes'])
        ],
        [
            'title' => __('Natural Beauty'),
            'data' => maybe_unserialize($post['nearby_natural_beauty'])
        ],
        [
            'title' => __('Closest Airports'),
            'data' => maybe_unserialize($post['nearby_airports'])
        ]
    ]
@endphp
@if(!empty($nearby_locations))
    @foreach($nearby_locations as $key => $val)
        @if(!empty($val['data']))
        <section class="feature nearby-location">
            <h2 class="section-title">{{$val['title']}}</h2>
            <div class="section-content">
                <ul>
                    @foreach($val['data'] as $k => $v)
                        <li>
                            <span class="addr">{{get_translate($v['title'])}}</span>
                            <span class="dist">{{$v['distance']}}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
        @endif
    @endforeach
@endif