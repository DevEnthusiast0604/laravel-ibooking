<?php
/**
 * Top bar 1
 */
?>
@if(get_option('top_bar_display') == "on")
   <?php
       $btn_text = get_option('top_bar_button_text');
       $btn_url = get_option('top_bar_button_url');
       $code = get_option('top_bar_promo_code');
   ?>
    <div id="top-bar-1" class="top-bar top-bar--1">
        <div class="top-bar__left">
            <div class="promo d-flex align-items-center">
                {{get_translate(get_option('top_bar_promo_text'))}}
                &nbsp;
                @if($code)
                    <a class="btn btn-primary btn-sm text-white btn-copy" data-toggle="tooltip"
                       title="{{__('Copy')}}">{{esc_html($code)}}</a>
                @endif
                @if($btn_text && $btn_url)
                    <a href="{{esc_url($btn_url)}}"
                       class="mx-1 btn btn-sm btn-danger">{{esc_html(get_translate($btn_text))}}</a>
                @endif
            </div>
        </div>
        <div class="top-bar__right">
            @if(is_multi_language())
                @php
                    $dropdown_langs = get_dropdown_language();
                @endphp
                @if($dropdown_langs)
                    {!! $dropdown_langs !!}
                @endif
            @endif

            @if(is_multi_currency())
              <?php echo get_dropdown_currency(); ?>
            @endif
        </div>

    </div>
@endif