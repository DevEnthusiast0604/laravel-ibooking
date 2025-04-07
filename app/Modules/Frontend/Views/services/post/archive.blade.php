@extends('Frontend::layouts.master')

@section('title', $title)
@section('class_body', 'page-archive')

@php
    $feature_image = get_option('blog_feature_image');
    $feature_image = get_attachment_url($feature_image);
@endphp


@section('content')
    @if(!empty($feature_image))
        <div class="feature-image">
            <img src="{{$feature_image}}" alt="blog page" />
        </div>
    @endif
    @php
        if(!isset($type)){
            $type = '';
        }
        the_breadcrumb([], 'term', ['type' => $type, 'title' => $title]);
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-lg-9 pb-5">
                <h2 class="archive-title">
                    @if(isset($type) && !empty($type))
                        @if($type == 'category')
                            {{sprintf(__('Category: %s'), $title)}}
                        @endif
                        @if($type == 'tag')
                                {{sprintf(__('Tag: %s'), $title)}}
                        @endif
                    @else
                        {{$title}}
                    @endif
                </h2>
                @if(!$posts->isEmpty())
                    <div class="row">
                        @foreach($posts as $post)
                            @php
                                $post_title = get_translate($post['post_title']);
                            @endphp
                        <div class="col-lg-12">
                            <div class="post-item">
                                <div class="thumbnail">
                                    <a href="{{get_post_permalink($post['post_slug'])}}">
                                    @if(!empty($post['thumbnail_id']))
                                        @php
                                            $thumbnail = get_attachment_url($post['thumbnail_id'], [840, 400])
                                        @endphp
                                        @if(!empty($thumbnail))
                                            <img src="{{$thumbnail}}" class="img-fluid" alt="{{$post_title}}" />
                                        @endif
                                    @endif
                                    <div class="date">{{date(get_date_format(), strtotime($post['created_at']))}}</div>
                                    </a>
                                </div>
                                <div class="info">
                                    <h3 class="post-title">
                                        <a href="{{get_post_permalink($post['post_slug'])}}">{{$post_title}}</a>
                                    </h3>
                                    <ul class="meta">
                                        <li>{{sprintf(__('By %s'), get_user_name($post['author']))}}</li>
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
                                                    {{__('On ')}}{!! implode(', ', $cates) !!}
                                                </li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {!! $posts->onEachSide(1)->links() !!}
                @else
                    <div class="alert alert-warning mt-4">{{__('No posts found!')}}</div>
                @endif
            </div>
            <div class="col-lg-3">
                <div class="siderbar-single">
                    @include('Frontend::services.post.sidebar')
                </div>
            </div>
        </div>
    </div>
@stop

