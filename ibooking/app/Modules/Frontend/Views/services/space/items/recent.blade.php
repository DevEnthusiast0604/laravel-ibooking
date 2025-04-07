@if(is_enable_service(GMZ_SERVICE_SPACE))
    @php
        enqueue_scripts('match-height');
        $list_spaces = get_posts([
            'post_type' => GMZ_SERVICE_SPACE,
            'posts_per_page' => 3,
            'status' => 'publish'
        ]);
        $search_url = url('space-search');
    @endphp
    @if(!$list_spaces->isEmpty())
        <section class="list-space list-space--grid py-40 bg-gray-100">
            <div class="container">
                <h2 class="section-title mb-20">{{__('List Of Space')}}</h2>
                <div class="row">
                    @foreach($list_spaces as $item)
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            @include('Frontend::services.space.items.grid-item')
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endif