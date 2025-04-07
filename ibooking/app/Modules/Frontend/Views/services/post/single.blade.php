@extends('Frontend::layouts.master')

@section('title', get_translate($post['post_title']))
@section('class_body', 'single-post')

@php
    $post_content = get_translate($post['post_content']);
    $post_title = get_translate($post['post_title']);
@endphp

@section('content')
    @if(!empty($post['thumbnail_id']))
        @php
            $thumbnail = get_attachment_url($post['thumbnail_id']);
        @endphp
        <div class="feature-image">
            <img src="{{$thumbnail}}" alt="{{$post_title}}" />
        </div>
    @endif
    @php
        the_breadcrumb($post, 'pages');
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-lg-9 pb-5">
                <h1 class="post-title">
                    {{$post_title}}
                </h1>
                <ul class="meta">
                   <li>
                       <div class="value">
                           {{get_user_name($post['author'])}}
                       </div>
                       <div class="label">
                           {{__('Author')}}
                       </div>
                   </li>
                   <li>
                       <div class="value">
                           {{date(get_date_format(), strtotime($post['created_at']))}}
                       </div>
                       <div class="label">
                           {{__('Date')}}
                       </div>
                   </li>
                    @if(!empty($post['post_category']))
                        @php
                            $cate_str = explode(',', $post['post_category']);
                            $cates = [];
                        @endphp
                        @foreach($cate_str as $cate)
                            @php
                                $term = get_term('id', $cate);
                                if(!is_null($term)){
                                    array_push($cates, '<a href="'. url('category/' . $term->term_name) .'">'. get_translate($term->term_title) .'</a>');
                                }
                            @endphp
                        @endforeach
                        @if(!empty($cates))
                            <li>
                                <div class="value">
                                    {!! implode(', ', $cates) !!}
                                </div>
                                <div class="label">
                                    {{__('Category')}}
                                </div>
                            </li>
                        @endif
                    @endif
                    <li>
                        @php
                            $number_comment = get_comment_number($post['id'], 'post');
                        @endphp
                        <div class="value">
                           {{$number_comment}}
                        </div>
                        <div class="label">
                            {{__('Comments')}}
                        </div>
                    </li>
                </ul>
                @if(!empty($post_content))
                    <section class="description">
                        <div class="section-content">
                            <?php echo $post_content; ?>
                        </div>
                    </section>
                @endif

                @if(!empty($post['post_tag']))
                    @php
                        $tag_str = explode(',', $post['post_tag']);
                        $tags = [];
                    @endphp
                    @foreach($tag_str as $tag)
                        @php
                            $term = get_term('id', $tag);
                            if(!is_null($term)){
                                array_push($tags, '<a class="tag-item" href="'. url('tag/' . $term->term_name) .'">'. esc_html(get_translate($term->term_title)) .'</a>');
                            }
                        @endphp
                    @endforeach
                    @if(!empty($tags))
                        <div class="post-tags">
                            {{__('Tags ')}}{!! implode(' ', $tags) !!}
                        </div>
                    @endif
                @endif
                @include('Frontend::services.post.comment')
            </div>
            <div class="col-lg-3">
                <div class="siderbar-single">
                @include('Frontend::services.post.sidebar')
                </div>
            </div>
        </div>
    </div>
@stop

