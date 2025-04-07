    @php
        $verify_codes = get_seo_verify_config();
    @endphp
    @if(!empty($verify_codes['google']))
            <meta name="google-site-verification" content="{{$verify_codes['google']}}" />
    @endif
    @if(!empty($verify_codes['bing']))
        <meta name="msvalidate.01" content="{{$verify_codes['bing']}}" />
    @endif
    @if(!empty($verify_codes['yandex']))
        <meta name="yandex-verification" content="{{$verify_codes['yandex']}}" />
    @endif
    @if(!empty($verify_codes['baidu']))
        <meta name="baidu-site-verification" content="{{$verify_codes['baidu']}}" />
    @endif

