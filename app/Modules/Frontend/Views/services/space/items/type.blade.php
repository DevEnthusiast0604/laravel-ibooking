@if(is_enable_service(GMZ_SERVICE_SPACE))
    @php
        enqueue_scripts('match-height');
        $space_types = get_terms('name', 'space-type', 'full');
        $search_url = url('space-search');
    @endphp
    @if(!$space_types->isEmpty())
        <section class="space-type">
            <div class="container py-40">
                <h2 class="section-title mb-20">{{__('Space Types')}}</h2>
                <div class="row">
                    @foreach($space_types as $item)
                        @php
                            $img = get_attachment_url($item->term_image, [250, 150]);
                            $term_title = get_translate($item->term_title);
                            $search_url = add_query_arg(['space_type' => $item->id], $search_url);
                        @endphp
                        <div class="col-lg-2 col-md-4 col-6">
                            <div class="space-type__item" data-plugin="matchHeight">
                                @if(!empty($img))
                                    <div class="space-type__thumbnail">
                                        <a href="{{$search_url}}">
                                            <img class="_image-space" src="{{$img}}" alt="{{$term_title}}">
                                        </a>
                                    </div>
                                @endif
                                <div class="space-type__info">
                                    <h3 class="space-type__name"><a href="{{$search_url}}">{{$term_title}}</a></h3>
                                    <div class="space-type__description">
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