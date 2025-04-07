@if(is_enable_service(GMZ_SERVICE_CAR))
    @php
        enqueue_scripts('match-height');
        $list_cars = get_posts([
            'post_type' => GMZ_SERVICE_CAR,
            'posts_per_page' => 3,
            'status' => 'publish'
        ]);
        $search_url = url('car-search');
    @endphp
    @if(!$list_cars->isEmpty())
        <section class="list-car list-car--grid py-40 bg-gray-100">
            <div class="container">
                <h2 class="section-title mb-20">{{__('List Of Cars')}}</h2>
                <div class="row">
                    @foreach($list_cars as $item)
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            @include('Frontend::services.car.items.grid-item')
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endif