@if(is_enable_service(GMZ_SERVICE_BEAUTY))
    @php
        enqueue_scripts('match-height');
        $list_beauty = get_posts([
            'post_type' => GMZ_SERVICE_BEAUTY,
            'posts_per_page' => 3,
            'status' => 'publish'
        ]);
        $search_url = url('beauty-search');
    @endphp
    @if(!$list_beauty->isEmpty())
        <section class="list-beauty list-beauty--grid py-40 bg-gray-100">
            <div class="container">
                <h2 class="section-title mb-20">{{__('List Of Beauty')}}</h2>
                <div class="row">
                    @foreach($list_beauty as $item)
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            @include('Frontend::services.beauty.items.grid-item')
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endif