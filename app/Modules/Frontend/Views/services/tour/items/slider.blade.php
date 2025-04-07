<section class="hero-slider">
    @php
        $slider = get_option('tour_slider');
        $galleries = [];
        if(!empty($slider)){
            $slider = explode(',', $slider);
            if(!empty($slider)){
                foreach($slider as $item){
                    $url = get_attachment_url($item, [1920, 768]);
                    if(!empty($url)){
                        array_push($galleries, $url);
                    }
                }
            }
        }
        $text_slider = get_translate(get_option('tour_slider_text'));
    @endphp
    <div class="container-fluid no-gutters p-0">
        @if(!empty($galleries))
            <div class="slider" data-plugin="slick">
                @foreach($galleries as $item)
                    <div class="item">
                        <img src="{{$item}}" alt="tour slider">
                    </div>
                @endforeach
            </div>
        @endif
        <div class="search-center">
                <div class="container">
                    <div class="search-form-wrapper">
                        <div class="tour-search-form">
                            @if(!empty($text_slider))
                                <p class="_title">{{$text_slider}}</p>
                            @endif
                            @php
                                enqueue_styles([
                                   'mapbox-gl',
                                   'mapbox-gl-geocoder'
                                ]);
                                enqueue_scripts([
                                   'mapbox-gl',
                                   'mapbox-gl-geocoder'
                                ]);
                            @endphp
                            @include('Frontend::services.tour.search-form')
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>