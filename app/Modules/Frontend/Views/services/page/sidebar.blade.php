@php
    $cates = get_terms('name', 'post-category', 'full');
@endphp
@if(!$cates->isEmpty())
<div class="widget-item">
    <h4 class="widget-title">{{__('Categories')}}</h4>
    <div class="widget-content">
        <ul>
        @foreach($cates as $key => $val)
            <li><a href="{{url('category/' . $val->term_name)}}">{{get_translate($val->term_title)}}</a></li>
        @endforeach
        </ul>
    </div>
</div>
@endif

@php
    $posts = get_posts([
        'post_type' => 'post',
        'posts_per_page' => 5,
        'orderby' => 'id',
        'order' => 'DESC'
    ]);
@endphp
@if(!$posts->isEmpty())
<div class="widget-item widget-recent-post">
    <h4 class="widget-title">{{__('Recent posts')}}</h4>
    <div class="widget-content">
        @foreach($posts as $item)
            @php
                $image = get_attachment_url($item['thumbnail_id'], [100, 100]);
                $post_title = get_translate($item['post_title']);
            @endphp
            <div class="post-item">
                <div class="thumbnail">
                    <a href="{{get_post_permalink($item['post_slug'])}}">
                        <img src="{{$image}}" alt="{{$post_title}}" class="img-fluid"/>
                    </a>
                </div>
                <div class="info">
                    <h5 class="title">
                        <a href="{{get_post_permalink($item['post_slug'])}}">
                            {{$post_title}}
                        </a>
                    </h5>
                    <p class="date">{{date(get_date_format(), strtotime($item['created_at']))}}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif