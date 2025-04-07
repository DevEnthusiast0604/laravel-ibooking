<?php

use Illuminate\Support\Str;
use TorMorten\Eventy\Facades\Events as Eventy;
if(!class_exists('Assets')) {
    class Assets
    {
        static $inline_css;
        static $current_css_id;
        static $prefix_class = "gmz-";

        static function init()
        {
            Eventy::addAction('gmz_init_header', [__CLASS__, '_addCssHeader'], 10, 1);
        }

        static function _addCssHeader()
        {
            if(self::$inline_css)
                echo "<style>" . self::$inline_css . "</style>
";
        }

        static function build_css($cssID = null, $string = null, $screen = null, $effect = null)
        {
            self::$current_css_id = $cssID;
            if (!empty($string) && $screen == 'desktop') {
                self::$inline_css .= '.' . self::$prefix_class . self::$current_css_id . $effect . "{" . $string . "}";
            }
            if (!empty($string) && $screen == 'tablet') {
                self::$inline_css .= '@media(min-width: 768px and max-width: 1024px){'
                    . '.' . self::$prefix_class . self::$current_css_id . $effect
                    . "{" . $string . "}}";
            }
            if (!empty($string) && $screen == 'mobile') {
                self::$inline_css .= '@media(max-width: 767px){'
                    . '.' . self::$prefix_class . self::$current_css_id . $effect
                    . "{" . $string . "}}";
            }

            return self::$prefix_class . self::$current_css_id;
        }

        static function add_css($string = false)
        {
            self::$inline_css .= $string;

        }
    }

    Assets::init();
}
