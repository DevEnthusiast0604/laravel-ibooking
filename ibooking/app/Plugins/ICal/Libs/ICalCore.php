<?php
if (!defined('GMZPATH')) {
    exit;
}

use TorMorten\Eventy\Facades\Events as Eventy;

if (!class_exists('ICalCore')) {
    class ICalCore
    {
        private static $_inst;
        public $_assetUrl;
        private $_pluginName = '';
        public $pluginPath;

        public function __construct()
        {
            $this->_pluginName = basename(dirname(__DIR__));
            $this->pluginPath = dirname(__DIR__);
            $this->_assetUrl = 'plugins/' . strtolower($this->_pluginName) . '/';

            $this->enqueueScripts();
            Eventy::addFilter('gmz_settings', [$this, '_addICalSettings'], 20, 1);

            Eventy::addFilter('gmz_room_fillable', [$this, '_addFillableFields'], 20, 1);
            Eventy::addFilter('gmz_apartment_fillable', [$this, '_addFillableFields'], 20, 1);
            Eventy::addFilter('gmz_space_fillable', [$this, '_addFillableFields'], 20, 1);
            Eventy::addFilter('gmz_tour_fillable', [$this, '_addFillableFields'], 20, 1);

            Eventy::addFilter('gmz_room_fields', [$this, '_addICalSettingFields'], 20, 1);
            Eventy::addFilter('gmz_apartment_fields', [$this, '_addICalSettingFields'], 20, 1);
            Eventy::addFilter('gmz_space_fields', [$this, '_addICalSettingFields'], 20, 1);
            Eventy::addFilter('gmz_tour_fields', [$this, '_addICalSettingFields'], 20, 1);

            Eventy::addAction('gmz_screen_edit_room', [$this, '_addScriptEditService'], 20, 1);
            Eventy::addAction('gmz_screen_edit_apartment', [$this, '_addScriptEditService'], 20, 1);
            Eventy::addAction('gmz_screen_edit_space', [$this, '_addScriptEditService'], 20, 1);
            Eventy::addAction('gmz_screen_edit_tour', [$this, '_addScriptEditService'], 20, 1);

            Eventy::addAction('gmz_room_custom_price_meta_tab', [$this, '_addICalURLService'], 20, 1);
            Eventy::addAction('gmz_apartment_custom_price_meta_tab', [$this, '_addICalURLService'], 20, 1);
            Eventy::addAction('gmz_space_custom_price_meta_tab', [$this, '_addICalURLService'], 20, 1);
            Eventy::addAction('gmz_tour_custom_price_meta_tab', [$this, '_addICalURLService'], 20, 1);
        }

        public function _addFillableFields($fillable)
        {
            $fillable[] = 'ical';
            return $fillable;
        }

        public function _addICalURLService($post)
        {
            echo view('Plugin.' . $this->_pluginName . '::meta.ical', $post);
        }

        public function _addScriptEditService()
        {
            admin_enqueue_styles('ical-css');
            admin_enqueue_scripts('ical-js');
        }

        public function enqueueScripts()
        {
            \App\Modules\Backend\Controllers\ScriptController::inst()->_addStyle('ical-css', asset($this->_assetUrl . 'css/main.css'));
            \App\Modules\Backend\Controllers\ScriptController::inst()->_addScript('ical-js', asset($this->_assetUrl . 'js/main.js'));
        }

        public function _addICalSettingFields($settings)
        {
            $settings['custom_price']['fields'][] = [
                'id' => 'ical',
                'type' => 'list_item',
                'label' => __('iCal Auto-Sync'),
                'translation' => true,
                'binding' => 'name',
                'fields' => [
                    [
                        'id' => 'name',
                        'label' => __('Name'),
                        'type' => 'text',
                        'layout' => 'col-12 col-md-6'
                    ],
                    [
                        'id' => 'type',
                        'label' => __('Calendar Type'),
                        'type' => 'select',
                        'layout' => 'col-12 col-md-6',
                        'choices' => [
                            'google' => __('Google Calendar'),
                            'airbnb' => __('Airbnb Calendar'),
                            'other' => __('Other Calendar'),
                        ],
                        'break' => true,
                    ],
                    [
                        'id' => 'url',
                        'label' => __('iCal URL'),
                        'type' => 'text',
                        'layout' => 'col-12',
                    ]
                ],
                'layout' => 'col-12 mt-5',
                'break' => true
            ];
            return $settings;
        }

        public function _addICalSettings($settings)
        {
            $settings['fields'][] = [
                'id' => 'ical_heading',
                'label' => __('iCal auto-sync time'),
                'type' => 'heading',
                'layout' => 'col-12 col-md-8',
                'std' => '',
                'section' => 'service_options',
            ];
            $settings['fields'][] = [
                'id' => 'ical_time_value',
                'label' => __('Time Value'),
                'type' => 'number',
                'layout' => 'col-12 col-md-3',
                'std' => '1',
                'break' => true,
                'min_max_step' => [1, 60, 1],
                'section' => 'service_options',
            ];
            $settings['fields'][] = [
                'id' => 'ical_time_type',
                'label' => __('Time Type'),
                'type' => 'select',
                'layout' => 'col-12 col-md-3',
                'std' => 'hour',
                'break' => true,
                'choices' => ['hour' => __('Hour'), 'minute' => __('Minute')],
                'section' => 'service_options',
            ];

            return $settings;
        }

        public static function inst()
        {
            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }
            return self::$_inst;
        }
    }

    ICalCore::inst();
}