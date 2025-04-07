@php
    $google_code = get_opt('seo_google_code', '', false);
    $bing_code = get_opt('seo_bing_code', '', false);
    $yandex_code = get_opt('seo_yandex_code', '', false);
    $baidu_code = get_opt('seo_baidu_code', '', false);
@endphp
<h4 class="mb-4">{{__('Webmaster Tools verification')}}</h4>
<div class="form-group mb-4">
    <label for="google_code">{{__('Google verification code')}}</label>
    <input name="google_code" class="form-control" id="google_code" type="text" value="{{$google_code}}"/>
</div>
<div class="form-group mb-4">
    <label for="bing_code">{{__('Bing verification code')}}</label>
    <input name="bing_code" class="form-control" id="bing_code" type="text" value="{{$bing_code}}"/>
</div>
<div class="form-group mb-4">
    <label for="yandex_code">{{__('Yandex verification code')}}</label>
    <input name="yandex_code" class="form-control" id="yandex_code" type="text" value="{{$yandex_code}}"/>
</div>
<div class="form-group mb-4">
    <label for="baidu_code">{{__('Baidu verification code')}}</label>
    <input name="baidu_code" class="form-control" id="baidu_code" type="text" value="{{$baidu_code}}"/>
</div>