@if(is_enable_service(GMZ_SERVICE_CAR))
@php
enqueue_scripts('match-height');
$car_types = get_terms('name', 'car-type', 'full');
$search_url = url('car-search');
@endphp
@if(!$car_types->isEmpty())
<section class="car-type">
    <div class="container py-40">
        <h2 class="section-title mb-20">{{__('Car Types')}}</h2>
        <div class="row">
            @foreach($car_types as $item)
                @php
                $img = get_attachment_url($item->term_image, [300, 200]);
                $term_title = get_translate($item->term_title);
                $search_url = add_query_arg(['car_type' => $item->id], $search_url);
                @endphp
            <div class="col-lg-4 col-md-6">
                <div class="car-type__item" data-plugin="matchHeight">
                    <div class="car-type__left">
                        <h3 class="car-type__name"><a href="{{$search_url}}">{{$term_title}}</a></h3>
                        <div class="car-type__description">
                            {{get_translate($item->term_description)}}
                        </div>
                        <a href="{{$search_url}}" class="btn btn-primary car-type__detail">{{__('View Detail')}}</a>
                    </div>
                    <div class="car-type__right">
                        <a href="{{$search_url}}">
                            <img class="_image-car" src="{{$img}}" alt="{{$term_title}}">
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endif