@if(is_enable_service(GMZ_SERVICE_TOUR))
    @php
        enqueue_scripts('match-height');
        $tour_types = get_terms('name', 'tour-type', 'full');
        $search_url = url('tour-search');
    @endphp
    @if(!$tour_types->isEmpty())
        <section class="tour-type">
            <div class="container py-40">
                <h2 class="section-title mb-20">{{__('Tour Types')}}</h2>
                <div class="row">
                    @foreach($tour_types as $item)
                        @php
                            $img = get_attachment_url($item->term_image, [250, 300]);
                            $term_title = get_translate($item->term_title);
                            $search_url = add_query_arg(['tour_type' => $item->id], $search_url);
                        @endphp
                        <div class="col-lg-2 col-md-4 col-6">
                            <div class="tour-type__item" data-plugin="matchHeight">
                                @if(!empty($img))
                                    <div class="tour-type__thumbnail">
                                        <a href="{{$search_url}}">
                                            <img class="_image-tour" src="{{$img}}" alt="{{$term_title}}">
                                        </a>
                                    </div>
                                @endif
                                <div class="tour-type__info">
                                    <h3 class="tour-type__name"><a href="{{$search_url}}">{{$term_title}}</a></h3>
                                    <div class="tour-type__description">
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