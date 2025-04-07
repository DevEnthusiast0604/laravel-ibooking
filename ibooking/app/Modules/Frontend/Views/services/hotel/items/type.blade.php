@if(is_enable_service(GMZ_SERVICE_HOTEL))
    @php
        enqueue_scripts('match-height');
        $property_types = get_terms('name', 'property-type', 'full');
        $search_url = url('hotel-search');
    @endphp
    @if(!$property_types->isEmpty())
        <section class="hotel-type">
            <div class="container py-40">
                <h2 class="section-title mb-20">{{__('Property Types')}}</h2>
                <div class="row">
                    @foreach($property_types as $item)
                        @php
                            $img = get_attachment_url($item->term_image, [250, 150]);
                            $term_title = get_translate($item->term_title);
                            $search_url = add_query_arg(['property_type' => $item->id], $search_url);
                        @endphp
                        <div class="col-lg-2 col-md-4 col-6">
                            <div class="hotel-type__item" data-plugin="matchHeight">
                                @if(!empty($img))
                                    <div class="hotel-type__thumbnail">
                                        <a href="{{$search_url}}">
                                            <img class="_image-hotel" src="{{$img}}" alt="{{$term_title}}">
                                        </a>
                                    </div>
                                @endif
                                <div class="hotel-type__info">
                                    <h3 class="hotel-type__name"><a href="{{$search_url}}">{{$term_title}}</a></h3>
                                    <div class="hotel-type__description">
                                        {{get_translate($item->term_description)}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endif