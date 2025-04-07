@if(is_enable_service(GMZ_SERVICE_BEAUTY))
    @php
        enqueue_scripts('match-height');
        $beauty_types = get_terms('name', 'beauty-services', 'full');
        $search_url = url('beauty-search');
    @endphp
    @if(!$beauty_types->isEmpty())
        <section class="beauty-type">
            <div class="container py-40">
                <h2 class="section-title mb-20">{{__('Beauty Services')}}</h2>
                <div class="row">
                    @foreach($beauty_types as $item)
                        @php
                            $img = get_attachment_url($item->term_image, [250, 300]);
                            $term_title = get_translate($item->term_title);
                            $search_url = add_query_arg(['service' => $item->id], $search_url);
                        @endphp
                        <div class="col-lg-2 col-md-4 col-6">
                            <div class="beauty-type__item" data-plugin="matchHeight">
                                @if(!empty($img))
                                    <div class="beauty-type__thumbnail">
                                        <a href="{{$search_url}}">
                                            <img class="_image-beauty" src="{{$img}}" alt="{{$term_title}}">
                                        </a>
                                    </div>
                                @endif
                                <div class="beauty-type__info">
                                    <h3 class="beauty-type__name"><a href="{{$search_url}}">{{$term_title}}</a></h3>
                                    <div class="beauty-type__description">
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