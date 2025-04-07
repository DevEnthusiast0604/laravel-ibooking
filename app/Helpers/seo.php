<?php
if(!function_exists('get_seo_separator')){
    function get_seo_separator($key = ''){
        $list = [
            'dash' => '-',
            'ndash' => '–',
            'mdash' => '—',
            'colon' => ':',
            'middot' => '·',
            'bull' => '•',
            'star' => '*',
            'smstar' => '⋆',
            'pipe' => '|',
            'tilde' => '~',
            'laquo' => '«',
            'raquo' => '»',
            'lt' => '<',
            'gt' => '>'
        ];
        if(!empty($key)){
            if(isset($list[$key])) {
                return $list[$key];
            }else{
                return $list['dash'];
            }
        }
        return $list;
    }
}

if(!function_exists('is_seo_enable')){
    function is_seo_enable(){
        $cache = \Illuminate\Support\Facades\Cache::get('gmz_seo', []);
        if(isset($cache) && isset($cache['seo_enable'])){
            return $cache['seo_enable'];
        }else {
            $option = get_opt('seo_enable', 'off', false);
            $result = false;
            if ($option == 'on') {
                $result = true;
            }
            $cache['seo_enable'] = $result;
            \Illuminate\Support\Facades\Cache::put('gmz_seo', $cache);
            return $result;
        }
    }
}

if (!function_exists('get_seo_title_separator')) {
    function get_seo_title_separator()
    {
        if(is_seo_enable()) {
            $cache = \Illuminate\Support\Facades\Cache::get('gmz_seo', []);
            if (isset($cache) && isset($cache['seo_separator'])) {
                $separator = $cache['seo_separator'];
            } else {
                $option = get_opt('seo_separator', 'dash', false);
                $seo_cache['seo_separator'] = $option;
                \Illuminate\Support\Facades\Cache::put('gmz_seo', $seo_cache);
                $separator = $option;
            }

            return get_seo_separator($separator);
        }
        return get_seo_separator('dash');
    }
}

if(!function_exists('get_seo_page_config')){
    function get_seo_page_config($page_id){
        $cache = \Illuminate\Support\Facades\Cache::get('gmz_seo', []);
        if(isset($cache) && isset($cache['seo_page_' . $page_id])){
            return $cache['seo_page_' . $page_id];
        }else {
            $option = get_opt('seo_page_' . $page_id);
            $cache['seo_page_' . $page_id] = $option;
            \Illuminate\Support\Facades\Cache::put('gmz_seo', $cache);
            return $option;
        }
    }
}

if(!function_exists('get_seo_content_config')){
    function get_seo_content_config($page_id){
        $cache = \Illuminate\Support\Facades\Cache::get('gmz_seo', []);
        if(isset($cache) && isset($cache['seo_content_' . $page_id])){
            return $cache['seo_content_' . $page_id];
        }else {
            $option = get_opt('seo_content_' . $page_id);
            $cache['seo_content_' . $page_id] = $option;
            \Illuminate\Support\Facades\Cache::put('gmz_seo', $cache);
            return $option;
        }
    }
}

if(!function_exists('get_seo_verify_config')){
    function get_seo_verify_config(){
        $cache = \Illuminate\Support\Facades\Cache::get('gmz_seo', []);
        if(isset($cache) && isset($cache['seo_verify_code'])){
            return $cache['seo_verify_code'];
        }else {
            $option = [
                'google' => get_opt('seo_google_code', '', false),
                'bing' => get_opt('seo_bing_code', '', false),
                'yandex' => get_opt('seo_yandex_code', '', false),
                'baidu' => get_opt('seo_baidu_code', '', false)
            ];
            $cache['seo_verify_code'] = $option;
            \Illuminate\Support\Facades\Cache::put('gmz_seo', $cache);
            return $option;
        }
    }
}

if(!function_exists('seo_page_title')){
    function seo_page_title(){
        if(!is_seo_enable()){
            return false;
        }

        $current_route = Route::current()->getName();
        $page_config = admin_config('page', 'seo');
        $pages = $page_config['items'];

        $seo_title = false;
        if(isset($pages[$current_route])){
            $page = $pages[$current_route];
            $options = get_seo_page_config($page['id']);
            if(!empty($options['seo_enable']) && $options['seo_enable'] == 'on'){
                $seo_title = isset($options['seo_title']) ? $options['seo_title'] : '';
                $seo_title = seo_decode(get_translate($seo_title));
            }
        }

        $content_config = admin_config('content', 'seo');
        $contents = $content_config['items'];
        if(isset($contents[$current_route])){
            $content = $contents[$current_route];
            $options = get_seo_content_config($content['id']);
            if(!empty($options['seo_enable']) && $options['seo_enable'] == 'on'){
                global $post;
                $seo_title = isset($options['seo_title']) ? $options['seo_title'] : '';
                if($post && !empty($post['seo'])){
                    if(isset($post['seo']['seo_title'])){
                        $seo_title_temp = get_translate($post['seo']['seo_title']);
                        if(!empty($seo_title_temp)) {
                            $seo_title = $post['seo']['seo_title'];
                        }
                    }
                }
                $seo_title = seo_decode(get_translate($seo_title));
            }
        }

        return $seo_title;
    }
}

if(!function_exists('seo_meta')){
    function seo_meta(){
        if(!is_seo_enable()){
            return '';
        }
        $current_route = Route::current()->getName();
        $page_config = admin_config('page', 'seo');
        $pages = $page_config['items'];

        $seo_meta = '';
        if(isset($pages[$current_route])){
            $page = $pages[$current_route];
            $options = get_seo_page_config($page['id']);
            if(!empty($options['seo_enable']) && $options['seo_enable'] == 'on'){
                $route = $current_route;
                if($route == 'home')
                    $route = '/';
                $seo_meta = view('Frontend::seo.meta', compact('options', 'route'))->render();
            }
        }

        $content_config = admin_config('content', 'seo');
        $contents = $content_config['items'];
        if(isset($contents[$current_route])){
            $content = $contents[$current_route];
            $options = get_seo_content_config($content['id']);
            if(!empty($options['seo_enable']) && $options['seo_enable'] == 'on'){
                $route = $current_route;
                $seo_meta = view('Frontend::seo.meta', compact('options', 'route'))->render();
            }
        }

        $seo_meta .= view('Frontend::seo.verify')->render();

        return $seo_meta;
    }
}

if(!function_exists('seo_decode')){
    function seo_decode($text){
        global $category, $tag, $post;
        $title = '';
        $description = '';
        if($category){
            $title = get_translate($category->term_title);
            $description = get_translate($category->term_description);
        }
        if($tag){
            $title = get_translate($tag->term_title);
            $description = get_translate($tag->term_description);
        }

        if($post){
            $title = get_translate($post['post_title']);
            $description = isset($post['post_description']) ? get_translate($post['post_description']) : '';
        }

        $codes = [
            '{title}' => $title,
            '{description}' => $description,
            '{site_name}' => get_translate(get_option('site_name')),
            '{site_description}' => get_translate(get_option('site_description')),
            '{separator}' => get_seo_title_separator()
        ];
        return str_replace(array_keys($codes), array_values($codes), $text);
    }
}