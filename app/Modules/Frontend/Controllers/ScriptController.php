<?php

namespace App\Modules\Frontend\Controllers;

use TorMorten\Eventy\Facades\Events as Eventy;

class ScriptController
{
    static $_inst = null;
    private $_styles = [];
    private $_scripts = [];

    public function __construct()
    {
        $this->_enqueueScripts();
    }

    private function _enqueueScripts()
    {
        //jQuery
        $this->_addScript('jquery', asset('html/assets/vendor/jquery-3.5.1.min.js'), true);

        //Bootstrap
        $this->_addScript('boostrap', asset('html/assets/vendor/bootstrap-4.0.0/dist/js/bootstrap.bundle.min.js'), true);

        //Slick
        $this->_addStyle('slick', asset('html/assets/vendor/slick-1.8.1/slick.css'));
        $this->_addScript('slick', asset('html/assets/vendor/slick-1.8.1/slick.min.js'));

        //Glow Cookies
        $gdpr_enable = get_option('gdpr_enable', 'off');
        if ($gdpr_enable == 'on') {
            $this->_addStyle('glow-cookies', asset('vendors/glow-cookies/glowCookies.css'), true);
            $this->_addScript('glow-cookies', asset('vendors/glow-cookies/glowCookies.js'), true);
        }

        //Fotorama
        $this->_addStyle('fotorama', asset('vendors/fotorama-4.6.4/fotorama.css'));
        $this->_addScript('fotorama', asset('vendors/fotorama-4.6.4/fotorama.js'));

        //Daterangepicker
        $this->_addStyle('daterangepicker', asset('html/assets/vendor/daterangepicker-master/daterangepicker.css'));
        $this->_addScript('moment', asset('html/assets/vendor/daterangepicker-master/moment.min.js'));
        $this->_addScript('daterangepicker', asset('html/assets/vendor/daterangepicker-master/daterangepicker.js'));

        //Magnific Popup
        $this->_addStyle('magnific-popup', asset('vendors/magnific-popup/magnific-popup.css'), true);
        $this->_addScript('magnific-popup', asset('vendors/magnific-popup/magnific-popup.js'), true);

        //Mapbox
        $this->_addScript('mapbox-gl', asset('vendors/mapbox/mapbox-gl.js'));
        $this->_addScript('mapbox-gl-geocoder', asset('vendors/mapbox/mapbox-gl-geocoder.js'));
        $this->_addStyle('mapbox-gl', asset('vendors/mapbox/mapbox-gl.css'));
        $this->_addStyle('mapbox-gl-geocoder', asset('vendors/mapbox/mapbox-gl-geocoder.css'));

        //Ion-RangeSlider
        $this->_addStyle('icon.rangeSlider', asset('html/assets/vendor/ion.rangeSlider-master/css/ion.rangeSlider.min.css'));
        $this->_addScript('icon.rangeSlider', asset('html/assets/vendor/ion.rangeSlider-master/js/ion.rangeSlider.min.js'));

        //Nicescroll
        $this->_addScript('jquery.nicescroll', asset('html/assets/vendor/jquery.nicescroll.min.js'));

        //Fontawesome
        $this->_addStyle('font-awesome', asset('html/assets/vendor/font-awesome-5/css/all.css'), true);

        //Flat Icon
        $this->_addStyle('flat-icon', asset('vendors/flag-icon/css/flag-icon.css'), true);

        $this->_addScript('match-height', asset('vendors/matchHeight.js'));

        //magnific-popup
        $this->_addStyle('magnific-popup', asset('html/assets/vendor/magnific-popup/magnific-popup.css'));
        $this->_addScript('magnific-popup', asset('html/assets/vendor/magnific-popup/magnific-popup.js'));
        $this->_addScript('bootstrap-validate', asset('js/bootstrap-validate.js'), true);

        //Select 2
        $this->_addStyle('select2', asset('public/admin/plugins/select2/select2.min.css'));
        $this->_addScript('select2', asset('admin/plugins/select2/select2.min.js'));

        //Main
        $this->_addStyle('bootstrap', asset('/html/assets/vendor/bootstrap-4.0.0/dist/css/bootstrap.min.css'), true);
        $this->_addStyle('installer', asset('html/assets/css/installer.css'));
        $this->_addStyle('main', asset('html/assets/css/main.css'), true, GMZ_VERSION);
        $this->_addScript('main', asset('html/assets/js/main.js'), true, GMZ_VERSION);
        $this->_addScript('custom', asset('html/assets/js/custom.js'), true, GMZ_VERSION);

        //jQuery ui
        $this->_addScript('jquery-ui', asset('vendors/jquery-ui/jquery-ui.min.js'));
        $this->_addStyle('jquery-ui', asset('vendors/jquery-ui/jquery-ui.min.css'));

        $this->_addScript('nested-sort-js', asset('vendors/jquery.mjs.nestedSortable.js'));

        $this->_addStyle('gmz-toast', asset('admin/plugins/toast/jquery.toast.min.css'), true);
        $this->_addScript('gmz-toast', asset('admin/plugins/toast/jquery.toast.min.js'), true);

        $this->_addStyle('gmz-toast1', asset('vendors/toastr/toastr.min.css'), true);
        $this->_addScript('gmz-toast1', asset('vendors/toastr/toastr.min.js'), true);

        $this->_addStyle('gmz-option', asset('css/option.css'), false, GMZ_VERSION);

        $this->_addStyle('flatpickr', asset('admin/plugins/flatpickr/flatpickr.css'));
        $this->_addStyle('custom-flatpickr', asset('admin/plugins/flatpickr/custom-flatpickr.css'));
        $this->_addScript('flatpickr', asset('admin/plugins/flatpickr/flatpickr.js'));

        $this->_addStyle('gmz-switches', asset('admin/assets/css/forms/switches.css'));

        $this->_addStyle('gmz-dropzone', asset('admin/plugins/dropzone/dropzone.min.css'));
        $this->_addScript('gmz-dropzone', asset('admin/plugins/dropzone/dropzone.min.js'));

        $this->_addStyle('gmz-quill', asset('admin/plugins/editors/quill/quill.snow.css'));
        $this->_addScript('gmz-quill', asset('admin/plugins/editors/quill/quill.js'));
        $this->_addScript('gmz-quill-image-resize', asset('vendors/image-resize.min.js'));

        $this->_addScript('gmz-spectrum', asset('admin/plugins/spectrum/spectrum.js'));
        $this->_addStyle('gmz-spectrum', asset('admin/plugins/spectrum/spectrum.css'));

        $this->_addStyle('gmz-custom-accordions', asset('admin/assets/css/components/tabs-accordian/custom-accordions.css'));


        $this->_addScript('gmz-option', asset('js/option.js'), false, GMZ_VERSION);
        //End check

        //Search
        $this->_addScript('gmz-search-car', asset('html/assets/js/search/car.js'));
        $this->_addScript('gmz-search-apartment', asset('html/assets/js/search/apartment.js'));
        $this->_addScript('gmz-search-hotel', asset('html/assets/js/search/hotel.js'));
        $this->_addScript('gmz-search-space', asset('html/assets/js/search/space.js'));
        $this->_addScript('gmz-search-beauty', asset('html/assets/js/search/beauty.js'));
        $this->_addScript('gmz-search-tour', asset('html/assets/js/search/tour.js'));

        //stripe
        $stripeEnabled = get_option('payment_stripe_enable', 'off');
        if ($stripeEnabled == 'on') {
            $this->_addScript('stripe', 'https://js.stripe.com/v3/', false);
            $this->_addScript('stripe-client', asset('html/assets/js/stripe-client.js'), false);
        }
    }

    public function setQueueStyle($styles)
    {
        if (!empty($styles)) {
            if (is_array($styles)) {
                foreach ($styles as $item) {
                    if (isset($this->_styles[$item])) {
                        $this->_styles[$item]['queue'] = true;
                    }
                }
            } else {
                if (isset($this->_styles[$styles])) {
                    $this->_styles[$styles]['queue'] = true;
                }
            }
        }
    }

    public function setQueueScript($scripts)
    {
        if (!empty($scripts)) {
            if (is_array($scripts)) {
                foreach ($scripts as $item) {
                    if (isset($this->_scripts[$item])) {
                        $this->_scripts[$item]['queue'] = true;
                    }
                }
            } else {
                if (isset($this->_scripts[$scripts])) {
                    $this->_scripts[$scripts]['queue'] = true;
                }
            }
        }
    }

    public function setBDQueueStyle($styles)
    {
        $need_enqueues = [];
        if (!empty($styles)) {
            if (is_array($styles)) {
                foreach ($styles as $item) {
                    if (isset($this->_styles[$item])) {
                        $this->_styles[$item]['queue'] = true;
                        $need_enqueues[] = $this->_styles[$item];
                    }
                }
            } else {
                if (isset($this->_styles[$styles])) {
                    $this->_styles[$styles]['queue'] = true;
                    $need_enqueues[] = $this->_styles[$styles];
                }
            }
        }
        return $need_enqueues;
    }

    public function setBDQueueScript($scripts)
    {
        $need_enqueues = [];
        if (!empty($scripts)) {
            if (is_array($scripts)) {
                foreach ($scripts as $item) {
                    if (isset($this->_scripts[$item])) {
                        $this->_scripts[$item]['queue'] = true;
                        $need_enqueues[] = $this->_scripts[$item];
                    }
                }
            } else {
                if (isset($this->_scripts[$scripts])) {
                    $this->_scripts[$scripts]['queue'] = true;
                    $need_enqueues[] = $this->_scripts[$scripts];
                }
            }
        }
        return $need_enqueues;
    }

    public function initHeader()
    {
        echo view('Backend::components.styles', ['styles' => $this->_styles]);
        Eventy::action('gmz_init_header');
        $this->_customCss();
        $this->_customHeaderCode();
        ?><script>
        var gmz_params = {
            mapbox_token: '<?php echo get_option('mapbox_token'); ?>',
            i18n: {
                guest: '<?php echo __('Guest') ?>',
                guests: '<?php echo __('Guests') ?>',
                infant: '<?php echo __('Infant') ?>',
                infants: '<?php echo __('Infants') ?>',
                adult: '<?php echo __('Adult') ?>',
                adults: '<?php echo __('Adults') ?>',
                children: '<?php echo __('Children') ?>',
                featured: '<?php echo __('Featured') ?>',
                passenger: '<?php echo __('Passenger') ?>',
                door: '<?php echo __('Door') ?>',
                baggage: '<?php echo __('Baggage') ?>',
                gearShift: '<?php echo __('Gear Shift') ?>',
                bedroom: '<?php echo __('Bedroom') ?>',
                bathroom: '<?php echo __('Bathroom') ?>',
                size: '<?php echo __('Size') ?>',
                perDay: '<?php echo __('per day') ?>',
                viewDetail: '<?php echo __('View Detail') ?>',
                duration: '<?php echo __('Duration') ?>',
                groupSize: '<?php echo __('Group Size') ?>',
                from: '<?php echo __('From') ?>',
            }
        };

        var localeDateRangePicker = {
            format: "YYYY-MM-DD",
            direction: 'ltr',
            applyLabel: '<?php echo __('Apply') ?>',
            cancelLabel: '<?php echo __('Cancel') ?>',
            fromLabel: '<?php echo __('From') ?>',
            toLabel: '<?php echo __('To') ?>',
            customRangeLabel: '<?php echo __('Custom') ?>',
            daysOfWeek: [
                '<?php echo __('Sun') ?>',
                '<?php echo __('Mo') ?>',
                '<?php echo __('Tu') ?>',
                '<?php echo __('We') ?>',
                '<?php echo __('Th') ?>',
                '<?php echo __('Fr') ?>',
                '<?php echo __('Sa') ?>'
            ],
            monthNames: [
                '<?php echo __('January') ?>',
                '<?php echo __('February') ?>',
                '<?php echo __('March') ?>',
                '<?php echo __('April') ?>',
                '<?php echo __('May') ?>',
                '<?php echo __('June') ?>',
                '<?php echo __('July') ?>',
                '<?php echo __('August') ?>',
                '<?php echo __('September') ?>',
                '<?php echo __('October') ?>',
                '<?php echo __('November') ?>',
                '<?php echo __('December') ?>'
            ],
            firstDay: 1,
            today: '<?php echo __('Today') ?>'
        };
    </script>
    <?php
    $gdpr_enable = get_option('gdpr_enable', 'off');
    if ($gdpr_enable == 'on') {
        $policy_page = get_option('gdpr_policy_page');
        $link_policy_page = '#';
        if ($policy_page) {
            $page_object = get_post($policy_page, 'page');
            if ($page_object) {
                $link_policy_page = get_page_permalink($page_object['post_slug']);
            }
        }
        $hide_after_click = get_option('gdpr_hide_after_click', 'on');
        if ($hide_after_click == 'on') {
            $hide_after_click = true;
        } else {
            $hide_after_click = false;
        }
?><script>
        var gmz_gdpr_params = {
            enable: '<?php echo get_option('gdpr_enable', 'off'); ?>',
            hide_after_click: '<?php echo $hide_after_click; ?>',
            position: '<?php echo get_option('gdpr_position', 'left'); ?>',
            manage_text: '<?php echo get_translate(get_option('gdpr_manage_text')); ?>',
            banner_heading: '<?php echo get_translate(get_option('gdpr_banner_heading')); ?>',
            banner_description: '<?php echo get_translate(get_option('gdpr_banner_description')); ?>',
            banner_link_text: '<?php echo get_translate(get_option('gdpr_banner_link_text')); ?>',
            policy_page: '<?php echo $link_policy_page; ?>',
            button_accept_text: '<?php echo get_translate(get_option('gdpr_button_accept_text')); ?>',
            button_reject_text: '<?php echo get_translate(get_option('gdpr_button_reject_text')); ?>',
        }
    </script>
            <?php
        }
    }

    public function initFooter()
    {
        echo view('Backend::components.scripts', ['scripts' => $this->_scripts]);
        Eventy::action('gmz_init_footer');
        $this->_customFooterCode();
    }

    public function _addStyle($handle, $url, $queue = false, $v = '')
    {
        if (!isset($this->_styles[$handle])) {
            $this->_styles[$handle] = [
                'handle' => $handle,
                'url' => $url,
                'queue' => $queue,
                'v' => $v
            ];
        }
    }

    public function _addScript($handle, $url, $queue = false, $v = '')
    {
        if (!isset($this->_scripts[$handle])) {
            $this->_scripts[$handle] = [
                'url' => $url,
                'queue' => $queue,
                'v' => $v
            ];
        }
    }

    private function _customHeaderCode()
    {
        $custom_code = get_option('header_code', '');
        if (!empty($custom_code)) {
            echo $custom_code;
        }
    }

    private function _customFooterCode()
    {
        $custom_code = get_option('footer_code', '');
        if (!empty($custom_code)) {
            echo $custom_code;
        }
    }

    private function _customCss()
    {
        $main_color = get_option('main_color', '#1ea69a');
        $hsl = hexToHsl($main_color);
        $h = round($hsl[0]);
        $s = round($hsl[1] * 100) . '%';
        $l = round($hsl[2] * 100) . '%';
        echo "<style>
        :root {
            --primary: hsl($h, $s, $l);
            --primary-hover: hsl($h, $s, 40%);
        }
    </style>
    ";

        $css = get_option('custom_css');
        $css = json_decode($css);
        if (!empty($css)) {
            echo '<style>' . balance_tags($css) . '</style>
';
        }
    }

    public static function inst()
    {
        if (is_null(self::$_inst)) {
            self::$_inst = new self();
        }
        return self::$_inst;
    }
}