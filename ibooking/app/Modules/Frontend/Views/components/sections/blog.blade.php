@php
    enqueue_scripts('match-height');
    $list_blogs = get_posts([
        'post_type' => 'post',
        'posts_per_page' => 3
    ]);
@endphp
@if(!$list_blogs->isEmpty())
<section class="blog-list blog-list--grid py-40">
    <div class="container">
        <h2 class="section-title mb-20">{{__('List Of Blog')}}</h2>
        <div class="row">
            @foreach($list_blogs as $item)
                @php
                    $img = get_attachment_url($item['thumbnail_id'], [360, 240]);
                    $title = get_translate($item['post_title']);
                    $cates = $item['post_category'];
                    $cate_arr = [];
                    if(!empty($cates)){
                        $cates = explode(',', $cates);
                        foreach($cates as $cate){
                            $c = get_term('id', $cate);
                            if(!empty($c)){
                                 array_push($cate_arr, '<a href="'. url('category/' . $c->term_name) .'"><span>'. get_translate($c->term_title) .'</span></a>');
                            }
                        }
                    }
                    $post_description = get_translate($item['post_description']);
                    if(empty($post_description)){
                        $post_description = trim_text(get_translate($item['post_content']), 10);
                    }
                @endphp
                <div class="col-md-4">
                    <div class="blog-item blog-item--grid" data-plugin="matchHeight">
                        <div class="blog-item__thumbnail">
                            <a href="{{get_post_permalink($item['post_slug'])}}">
                                <img src="{{esc_url($img)}}" alt="{{esc_html($title)}}">
                            </a>
                        </div>
                        <div class="blog-item__details">
                            <a class="blog-item__label" href="{{url('blog')}}">{{__('News')}}</a>
                            <h3 class="blog-item__title"><a href="{{get_post_permalink($item['post_slug'])}}">{{esc_html($title)}}</a></h3>
                            <div class="blog-item__post-meta">
                                <span class="_date">{{__('On')}} {{esc_html(date('d-m-Y', strtotime($item['created_at'])))}}</span>
                                @if(!empty($cate_arr))
                                    <span class="_categories">
                                        {{__('In')}}
                                        {!! implode(', ', $cate_arr) !!}
                                    </span>
                                @endif
                            </div>
                            <p class="blog-item__excrept pt-3">
                                <a href="{{get_post_permalink($item['post_slug'])}}">
                                    {{ $post_description }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif