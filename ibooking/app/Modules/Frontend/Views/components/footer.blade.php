@php
    //widget nav
    $setting_widget_nav = [
       'footer_menu_1',
       'footer_menu_2',
       'footer_menu_3',
    ];
    foreach($setting_widget_nav as $value){
       $menu_id = get_option($value);
       $arr_widget_nav[] = [
           'label' => get_translate(get_option($value . '_heading')),
           'items' => get_menu_by_id($menu_id)
        ];
    }
    $copy_right = get_option('footer_copyright');
@endphp
<footer class="site-footer pt-60 pb-40">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                @foreach($arr_widget_nav as $menu)
                    @if(isset($menu['items']['menu_id']) && has_nav($menu['items']['menu_id']))
                    <div class="col-md-3">
                        <div class="widget widget-nav">
                            <h4 class="widget__title">{{$menu['label']}}</h4>
                                @php
                                    get_nav_by_id($menu['items']['menu_id']);
                                @endphp
                        </div>
                    </div>
                    @endif
                @endforeach
                @if(is_multi_language() || is_multi_currency())
                <div class="col-md-3">
                    <div class="widget widget-select">
                        @if(is_multi_language())
                            @php
                            $dropdown_langs = get_dropdown_language();
                            @endphp
                            @if($dropdown_langs)
                                <h4 class="widget__title">{{__('Language')}}</h4>
                                {!! $dropdown_langs !!}
                                <div class="mb-4"></div>
                            @endif
                        @endif

                        @if(is_multi_currency())
                        <h4 class="widget__title">{{__('Currencies')}}</h4>
                              <?php echo get_dropdown_currency(); ?>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="footer-bottom pt-30 pb-30">
        <div class="container">
            @if(isset($footer_menu['menu_id']) &&  has_nav($footer_menu['menu_id']))
                @php
                    get_nav_by_id($footer_menu['menu_id'],'menu-footer');
                @endphp
            @endif
            <div class="copyright text-center">
                @if(!empty($copy_right))
                {{ get_translate($copy_right) }}
                @else
                    ©{{date('Y')}} iBooking - All rights reserved.
                @endif
            </div>
            @php
                $social = get_option('social');
            @endphp
            @if($social)
            <ul class="social-footer">
                @foreach($social as $s)
                    <li>
                        <a href="{{$s['url']}}" title="{{get_translate($s['title'])}}">
                            @if(strpos($s['icon'], ' fa-'))
                                <i class="{{$s['icon']}} term-icon"></i>
                            @else
                                {!! get_icon($s['icon']) !!}
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
            @endif
                @action('gmz_after_footer_bottom')
        </div>
    </div>
</footer>
@action('gmz_after_footer')
