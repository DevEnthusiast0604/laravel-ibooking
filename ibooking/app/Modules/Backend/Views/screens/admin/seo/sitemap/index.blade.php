{{--Enable Option--}}
@php
    admin_enqueue_styles('gmz-switches');
    $enable_sitemap = get_opt('seo_enable_sitemap', 'off', false);
@endphp
<div class="gmz-field gmz-field-switcher">
    <label>{{__('Enable Sitemap')}}</label><br>
    <label class="gmz-switcher switch s-icons s-outline  s-outline-primary  mb-0">
        <input id="gmz-field-tax_included" type="checkbox" class="for-switcher" {{$enable_sitemap == 'on' ? 'checked' : ''}}>
        <span class="slider round"></span>
        <input type="hidden" name="seo_enable_sitemap" value="{{$enable_sitemap}}">
    </label>
</div>

<div class="row">
<div class="col-md-9 col-12">
    <div class="alert alert-success font-weight-bold d-flex align-items-center flex-wrap">
        <i class="fal fa-2x fa-info-circle mr-2"></i> {{__('Sitemap URL')}}:&nbsp;
        <a href="{{url('sitemap.xml')}}">{{url('sitemap.xml')}}</a>
    </div>
</div>
</div>