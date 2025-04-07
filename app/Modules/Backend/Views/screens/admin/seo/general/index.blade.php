{{--Enable Option--}}
@php
    admin_enqueue_styles('gmz-switches');
    $seo_enable = get_opt('seo_enable', 'off', false);
    $seo_robots = get_opt('seo_robots', '');
@endphp
<div class="gmz-field gmz-field-switcher">
    <label>{{__('Enable SEO Option')}}</label><br>
    <label class="gmz-switcher switch s-icons s-outline  s-outline-primary  mb-0">
        <input id="gmz-field-tax_included" type="checkbox" class="for-switcher" {{$seo_enable == 'on' ? 'checked' : ''}}>
        <span class="slider round"></span>
        <input type="hidden" name="seo_enable" value="{{$seo_enable}}">
    </label>
</div>

{{--Title Separator--}}
<div id="toggleAccordionOne" class="mt-4">
    <div class="card">
        <div class="card-header" id="headingSeparator">
            <section class="mb-0 mt-0">
                <div role="menu" class="" data-toggle="collapse" data-target="#defaultAccordionSeparator" aria-expanded="true" aria-controls="defaultAccordionSeparator">
                    <span class="item-title">
                       {{__('Title Separator')}}
                    </span>
                    <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up"><polyline points="18 15 12 9 6 15"></polyline></svg></div>
                </div>
            </section>
        </div>

        <div id="defaultAccordionSeparator" class="collapse show" aria-labelledby="headingSeparator" data-parent="#toggleAccordionOne">
            <div class="card-body">
                <p>{{__('Choose the symbol to use as your title separator. This will display, for instance, between your post title and site name. Symbols are shown in the size they\'ll appear in the search results.')}}</p>
                <div class="seo-list-separator">
                    <ul>
                        @php
                        $list_separator = get_seo_separator();
                        $current_separator = get_opt('seo_separator', 'dash', false);;
                        if(empty($current_separator)){
                            $current_separator = 'dash';
                        }
                        @endphp
                        @foreach($list_separator as $k => $v)
                            <li class="{{$k == $current_separator ? 'active' : ''}}" data-value="{{$k}}">{{$v}}</li>
                        @endforeach
                    </ul>
                    <input type="hidden" name="seo_separator" value="{{$current_separator}}"/>
                </div>
            </div>
        </div>
    </div>
</div>

{{--Robots.txt--}}
<div class="seo-robot mt-4">
    <div class="form-group">
        <label id="seo-robots">{{__('Robots.txt')}}</label>
        <textarea id="seo-robots" rows="15" name="seo_robot" class="form-control">{!! trim($seo_robots) !!}</textarea>
        <p class="mt-2">URL: <a href="{{url('robots.txt')}}" target="_blank" class="text-info">{{url('robots.txt')}}</a></p>
    </div>
</div>