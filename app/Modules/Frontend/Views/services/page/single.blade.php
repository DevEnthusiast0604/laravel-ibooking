@extends('Frontend::layouts.master')

@section('title', get_translate($post['post_title']))
@section('class_body', 'single-page')

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
                @if(!empty($post_content))
                    <section class="description">
                        <div class="section-content">
                            {!! balance_tags($post_content) !!}
                        </div>
                    </section>
                @endif
            </div>
            <div class="col-lg-3">
                <div class="siderbar-single">
                @include('Frontend::services.page.sidebar')
                </div>
            </div>
        </div>
    </div>
@stop

