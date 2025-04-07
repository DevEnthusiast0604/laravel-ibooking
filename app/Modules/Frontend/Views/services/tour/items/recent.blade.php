@if(is_enable_service(GMZ_SERVICE_TOUR))
    @php
        enqueue_scripts('match-height');
        $list_tours = get_posts([
            'post_type' => GMZ_SERVICE_TOUR,
            'posts_per_page' => 3,
            'status' => 'publish'
        ]);
        $search_url = url('tour-search');
    @endphp
    @if(!$list_tours->isEmpty())
        <section class="list-tour list-tour--grid py-40 bg-gray-100">
            <div class="container">
                <h2 class="section-title mb-20">{{__('List Of Tours')}}</h2>
                <div class="row">
                    @foreach($list_tours as $item)
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            @include('Frontend::services.tour.items.grid-item')
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endif