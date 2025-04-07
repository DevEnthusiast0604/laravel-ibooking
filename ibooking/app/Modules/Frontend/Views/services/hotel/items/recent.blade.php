@if(is_enable_service(GMZ_SERVICE_HOTEL))
    @php
        enqueue_scripts('match-height');
        $list_hotels = get_posts([
            'post_type' => GMZ_SERVICE_HOTEL,
            'posts_per_page' => 3,
            'status' => 'publish'
        ]);
        $search_url = url('hotel-search');
    @endphp
    @if(!$list_hotels->isEmpty())
        <section class="list-hotel list-hotel--grid py-40 bg-gray-100">
            <div class="container">
                <h2 class="section-title mb-20">{{__('List Of Hotels')}}</h2>
                <div class="row">
                    @foreach($list_hotels as $item)
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            @include('Frontend::services.hotel.items.grid-item')
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endif