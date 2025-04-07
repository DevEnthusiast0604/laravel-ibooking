@php
    $list_spaces = get_posts([
        'post_type' => GMZ_SERVICE_BEAUTY,
        'posts_per_page' => 3,
        'post_not_in' => [$post['id']],
        'terms' => [$post['branch']]
    ]);
enqueue_scripts('match-height');
$search_url = url('space-search');
@endphp
@if(!$list_spaces->isEmpty())
    <section class="list-space list-space--grid py-40 bg-gray-100 mb-0 nearby">
        <div class="container">
            <h2 class="section-title mb-20">{{__('Facility\'s other services')}}</h2>
            <div class="row">
                @foreach($list_spaces as $item)
                    @php
                        $img = get_attachment_url($item->thumbnail_id, [360, 240]);
                        $title = get_translate($item->post_title);
                        $type = get_term('id', $item->service);
                    @endphp
                    <div class="col-md-4">
                        @include('Frontend::services.beauty.items.grid-item')
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif