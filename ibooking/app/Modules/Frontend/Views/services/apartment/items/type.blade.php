@if(is_enable_service(GMZ_SERVICE_APARTMENT))
    @php
        enqueue_scripts('match-height');
        $apartment_types = get_terms('name', 'apartment-type', 'full');
        $search_url = url('apartment-search');
    @endphp
    @if(!$apartment_types->isEmpty())
        <section class="apartment-type">
            <div class="container py-40">
                <h2 class="section-title mb-20">{{__('Apartment Types')}}</h2>
                <div class="row">
                    @foreach($apartment_types as $item)
                        @php
                            $img = get_attachment_url($item->term_image, [250, 150]);
                            $term_title = get_translate($item->term_title);
                            $search_url = add_query_arg(['apartment_type' => $item->id], $search_url);
                        @endphp
                        <div class="col-lg-2 col-md-4 col-6">
                            <div class="apartment-type__item" data-plugin="matchHeight">
                                @if(!empty($img))
                                    <div class="apartment-type__thumbnail">
                                        <a href="{{$search_url}}">
                                            <img class="_image-apartment" src="{{$img}}" alt="{{$term_title}}">
                                        </a>
                                    </div>
                                @endif
                                <div class="apartment-type__info">
                                    <h3 class="apartment-type__name"><a href="{{$search_url}}">{{$term_title}}</a></h3>
                                    <div class="apartment-type__description">
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