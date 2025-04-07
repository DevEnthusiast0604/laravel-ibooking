@php
    global $category, $tag, $post;
    if($category){
        $route = url('category/' . $category->term_name);
    }
    if($tag){
        $route = url('tag/' . $tag->term_name);
    }
    if($post){
        $route = get_the_permalink($post['post_slug'], $post['post_type']);
    }

    $meta_description = isset($options['meta_description']) ? $options['meta_description'] : '';
    $seo_image_facebook = isset($options['seo_image_facebook']) ? $options['seo_image_facebook'] : '';
    $seo_title_facebook = isset($options['seo_title_facebook']) ? $options['seo_title_facebook'] : '';
    $meta_description_facebook = isset($options['meta_description_facebook']) ? $options['meta_description_facebook'] : '';

    $seo_image_twitter = isset($options['seo_image_twitter']) ? $options['seo_image_twitter'] : '';
    $seo_title_twitter = isset($options['seo_title_twitter']) ? $options['seo_title_twitter'] : '';
    $meta_description_twitter = isset($options['meta_description_twitter']) ? $options['meta_description_twitter'] : '';
    if($post && !empty($post['seo'])){
        if(isset($post['seo']['meta_description'])){
            $meta_description_temp = get_translate($post['seo']['meta_description']);
            if(!empty($meta_description_temp)){
                $meta_description = $post['seo']['meta_description'];
            }
        }
        if(!empty($post['seo']['seo_image_facebook'])){
            $seo_image_facebook = $post['seo']['seo_image_facebook'];
        }
        if(isset($post['seo']['seo_title_facebook'])){
            $seo_title_facebook_temp = get_translate($post['seo']['seo_title_facebook']);
            if(!empty($seo_title_facebook_temp)){
                $seo_title_facebook = $post['seo']['seo_title_facebook'];
            }
        }
        if(isset($post['seo']['meta_description_facebook'])){
            $meta_description_facebook_temp = get_translate($post['seo']['meta_description_facebook']);
            if(!empty($meta_description_facebook_temp)){
                $meta_description_facebook = $post['seo']['meta_description_facebook'];
            }
        }
        if(!empty($post['seo']['seo_image_twitter'])){
            $seo_image_twitter = $post['seo']['seo_image_twitter'];
        }
        if(isset($post['seo']['seo_title_twitter'])){
            $seo_title_twitter_temp = get_translate($post['seo']['seo_title_twitter']);
            if(!empty($seo_title_twitter_temp)){
                $seo_title_twitter = $post['seo']['seo_title_twitter'];
            }
        }
        if(isset($post['seo']['meta_description_twitter'])){
            $meta_description_twitter_temp = get_translate($post['seo']['meta_description_twitter']);
            if(!empty($meta_description_twitter_temp)){
                $meta_description_twitter = $post['seo']['meta_description_twitter'];
            }
        }
    }
@endphp
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
    <meta name="description" content="{{seo_decode(get_translate($meta_description))}}"/>
    @if(isset($route))<link rel="canonical" href="{{url($route)}}"/>@endif
    {{--Social--}}
    <meta property="og:locale" content="{{str_replace('_', '-', app()->getLocale())}}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="{{seo_decode(get_translate($seo_title_facebook))}}"/>
    <meta property="og:description" content="{{seo_decode(get_translate($meta_description_facebook))}}"/>
    @if(isset($route))<meta property="og:url" content="{{url($route)}}"/>@endif

    <meta property="og:site_name" content="{{get_translate(get_option('site_name', ''))}}"/>
    <meta property="article:published_time" content="{{date('c', time())}}"/>
    @if($seo_image_facebook)<meta property="og:image" content="<?php echo get_attachment_url($seo_image_facebook) ?>"/>@endif

    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="{{seo_decode(get_translate($seo_title_twitter))}}"/>
    <meta name="twitter:description" content="{{seo_decode(get_translate($meta_description_twitter))}}"/>
    @if($seo_image_twitter)<meta name="twitter:image" content="<?php echo get_attachment_url($seo_image_twitter) ?>"/>@endif

    @if(isset($route))<meta property="twitter:url" content="{{url($route)}}"/>@endif

