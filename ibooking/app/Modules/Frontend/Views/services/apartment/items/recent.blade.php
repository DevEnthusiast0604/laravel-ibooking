@if(is_enable_service(GMZ_SERVICE_APARTMENT))
    @php
        enqueue_scripts('match-height');
        $list_apartments = get_posts([
            'post_type' => GMZ_SERVICE_APARTMENT,
            'posts_per_page' => 3,
            'status' => 'publish',
            'is_featured' => 'on'
        ]);
        $search_url = url('apartment-search');
    @endphp
    @if(!$list_apartments->isEmpty())
        <section class="list-apartment list-apartment--grid py-40 bg-gray-100">
            <div class="container">
                <h2 class="section-title mb-20">{{__('List Of Apartments')}}</h2>
                <div class="row">
                    @foreach($list_apartments as $item)
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            @include('Frontend::services.apartment.items.grid-item')
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endif