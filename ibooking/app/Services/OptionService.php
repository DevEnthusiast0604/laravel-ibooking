<?php

namespace App\Services;

use App\Repositories\OptionRepository;

class OptionService extends AbstractService
{
    private static $_inst;
    protected $repository;
    private $optionName = 'gmz_options';

    public static function inst()
    {
        if (empty(self::$_inst)) {
            self::$_inst = new self();
        }
        return self::$_inst;
    }

    public function __construct()
    {
        $this->repository = OptionRepository::inst();
    }

    public function getCheckingEmailForm($request)
    {

        $data = [
            'title' => __('Checking Email'),
            'action' => dashboard_url('checking-email')
        ];
        return [
            'status' => 1,
            'html' => view('Backend::components.modal.checking-email-content', ['data' => $data])->render()
        ];
    }

    public function sortPayment($request)
    {
        $structure = $request->post('payment_structure', []);
        if (!empty($structure)) {
            update_opt('payment_structure', json_encode($structure));
        }

        return [
            'status' => 1,
            'message' => __('Sort payment successfully.')
        ];
    }

    public function getPaymentForm($request)
    {

        $data = [
            'title' => __('Sort Payment'),
            'action' => dashboard_url('sort-payment'),
            'payments' => \BaseGateway::inst()->getPaymentSettings()
        ];
        return [
            'status' => 1,
            'html' => view('Backend::components.modal.payment-content', ['data' => $data])->render()
        ];
    }

    public function getListItemHtml($request)
    {
        $id = $request->post('id', '');
        $fields = $request->post('fields', '');
        $html = '';
        if (!empty($fields) && !empty($id)) {
            $fields = json_decode(base64_decode($fields), true);
            $html = view('Backend::settings.fields.ajax.list-item-html', [
                'id' => $id,
                'fields' => $fields
            ])->render();
        }

        return [
            'status' => true,
            'html' => $html
        ];
    }

    private function mergeSettings($settings_config, $settings_db)
    {
        $settings = [];
        if (!empty($settings_config)) {
            foreach ($settings_config as $item) {
                if (isset($settings_db[$item['id']])) {
                    $settings[$item['id']] = $settings_db[$item['id']];
                } else {
                    $settings[$item['id']] = isset($item['std']) ? $item['std'] : '';
                }
            }
        }

        return $settings;
    }

    private function _fetchTranslation($field)
    {
        if ($field['type'] == 'list_item') {
            $value = request()->get($field['id'], '');
            $langs = get_languages();
            $return = [];
            if (count($langs) > 0) {

                $field_need_trans = [];
                foreach ($field['fields'] as $fkey => $fval) {
                    if (isset($fval['translation']) && $fval['translation']) {
                        array_push($field_need_trans, $fval['id']);
                    }
                }

                if (!empty($value)) {
                    foreach ($value as $key => $val) {
                        if (!empty($val)) {
                            foreach ($val as $key1 => $val1) {
                                if (in_array($key, $field_need_trans)) {
                                    $str = '';
                                    foreach ($val1 as $key2 => $val2) {
                                        $str .= '[:' . $langs[$key2] . ']' . $val2;
                                    }
                                    $str .= '[:]';
                                    $return[$key][$key1][0] = $str;
                                } else {
                                    $return[$key][$key1] = $val1;
                                }
                            }
                        }
                    }
                }
            }

            if (empty($return)) {
                $return = $value;
            }

            $list_item_data = [];

            if (is_array($return) && !empty($return)) {
                foreach ($return as $key => $val) {
                    foreach ($val as $child_key => $child_val) {
                        $list_item_data[$child_key][$key] = $child_val[0];
                    }
                }
            }

            return $list_item_data;

        } elseif ($field['type'] == 'location') {
            $return = [];
            $value = request()->get($field['id'], '');
            if (!empty($value['address']) && is_array($value['address'])) {
                $return['postcode'] = $value['postcode'];
                $return['lat'] = $value['lat'];
                $return['lng'] = $value['lng'];
                $return['zoom'] = $value['zoom'];

                $need_filter = ['address', 'city', 'state', 'country'];
                foreach ($need_filter as $item) {
                    $val_temp = '';
                    foreach ($value[$item] as $key => $val) {
                        $val_temp .= '[:' . $key . ']' . $val;
                    }
                    $val_temp .= '[:]';
                    $return[$item] = $val_temp;
                }
                return $return;
            } else {
                return $value;
            }
        } elseif ($field['type'] == 'term_price') {
            $termObject = get_terms($field['choices'], true);
            $termData = [];
            if (!empty($termObject)) {
                foreach ($termObject as $term) {
                    $termData[$term->term_id] = [
                        'title' => $term->term_title,
                        'price' => $term->term_price
                    ];
                }
            }

            $value = request()->get($field['id'], '');
            $return = [];
            if (!empty($value['price'])) {
                foreach ($value['price'] as $key => $val) {
                    $status = isset($value['id'][$key]) ? 'yes' : 'no';
                    if (!empty($val)) {
                        $price = (float)$val;
                    } else {
                        $price = $termData[$key]['price'];
                    }
                    $custom = empty($val) ? false : true;
                    $return[$key] = [
                        'choose' => $status,
                        'price' => $price,
                        'custom' => $custom
                    ];
                }
            }
            return serialize($return);
        } else {
            if (isset($field['translation']) && $field['translation']) {
                $value = set_translate($field['id']);
            } else {
                $value = request()->get($field['id'], '');
            }
        }
        return $value;
    }

    public function saveSettings($request)
    {
        $settings_config = get_config_settings()['fields'];
        $settings_db = $this->getOption($this->optionName, true);
        $options = $request->post('options', '');
        $options = json_decode(base64_decode($options), true);
        $settings_config_temp = $settings_config;
        foreach ($settings_config_temp as $item) {
            if ($item['type'] == 'tab') {
                if (!is_array($item['tabs']) && $item['tabs'] == 'payment_settings') {
                    $item['tabs'] = \BaseGateway::inst()->getPaymentSettings();
                }
                if (!empty($item['tabs'])) {
                    foreach ($item['tabs'] as $_item) {
                        if (!empty($_item['fields'])) {
                            foreach ($_item['fields'] as $_item_field) {
                                $settings_config[] = $_item_field;
                            }
                        }

                    }
                }
            }
        }

        $need_create = false;
        if (empty($settings_db) || $settings_db == -1) {
            $need_create = true;
            $settings_db = [];
        }

        $settings = $this->mergeSettings($settings_config, $settings_db);

        $post_data = $request->all();
        if (isset($post_data['_token'])) {
            unset($post_data['_token']);
        }

        if (!empty($options)) {
            foreach ($options as $key => $val) {
                if (isset($settings[$val['id']])) {
                    $option_value = $this->_fetchTranslation($val);

                    $settings[$val['id']] = $option_value;
                }
            }
        }

        $settings = serialize($settings);
        if ($need_create) {
            $updated = $this->repository->save([
                'name' => $this->optionName,
                'value' => $settings
            ]);
        } else {
            $updated = $this->repository->updateByWhere(
                ['name' => $this->optionName],
                ['value' => $settings]);
        }

        if ($updated) {
            $this->_configEmail($post_data);
            $this->_configIcal($post_data);
            return [
                'status' => true,
                'message' => __('Save changes successfully')
            ];
        }

        return [
            'status' => false,
            'message' => __('Save changes failed')
        ];
    }

    private function _configIcal($post_data)
    {
        if (isset($post_data['ical_time_value']) && isset($post_data['ical_time_type'])) {
            $icalType = $post_data['ical_time_type'];
            $icalValue = $post_data['ical_time_value'];
            set_env('ICAL_TYPE', $icalType);
            set_env('ICAL_VALUE', $icalValue);
        }
    }

    private function _configEmail($post_data)
    {
        if (isset($post_data['email_host']) && isset($post_data['email_username'])) {
            $email_host = isset($post_data['email_host']) ? $post_data['email_host'] : '';
            $email_username = isset($post_data['email_username']) ? $post_data['email_username'] : '';
            $email_password = isset($post_data['email_password']) ? $post_data['email_password'] : '';
            $email_port = isset($post_data['email_port']) ? $post_data['email_port'] : '';
            $email_encryption = isset($post_data['email_encryption']) ? $post_data['email_encryption'] : '';
            set_env('MAIL_HOST', $email_host);
            set_env('MAIL_USERNAME', $email_username);
            set_env('MAIL_PASSWORD', $email_password);
            set_env('MAIL_PORT', $email_port);
            set_env('MAIL_ENCRYPTION', $email_encryption);

            if (isset($post_data['enable_queue_mail']) && $post_data['enable_queue_mail'] == 'on') {
                set_env('QUEUE_CONNECTION', 'database');
            } else {
                set_env('QUEUE_CONNECTION', 'sync');
            }
        }
    }

    public function getOption($key, $unserialize = false)
    {
        $data = $this->repository->getOption($key);
        if (!is_null($data)) {
            $option = $data->getAttributes();
            if (!empty($option['value']) && $unserialize) {
                return maybe_unserialize($option['value']);
            }
            return $option['value'];
        }
        return '';
    }

    public function getIconsAction($request)
    {
        $types_input = $request->post('type', []);
        $categories_input = $request->post('category', []);
        if (!empty($types_input)) {
            $types_input = json_decode($types_input, true);
        }
        if (!empty($categories_input)) {
            $categories_input = json_decode($categories_input, true);
        }

        $types = [];
        if (empty($types_input)) {
            $types = array_values(get_icon_types());
        } else {
            $icon_types = get_icon_types();
            foreach ($types_input as $k => $v) {
                $type_temp = isset($icon_types[$v]) ? $icon_types[$v] : '';
                if (!empty($type_temp)) {
                    array_push($types, $type_temp);
                }
            }
        }

        $icons_yml = \Symfony\Component\Yaml\Yaml::parseFile(public_path('html/assets/vendor/font-awesome-5/categories.yml'));

        $checking_yml = \Symfony\Component\Yaml\Yaml::parseFile(public_path('html/assets/vendor/font-awesome-5/icons.yml'));

        $icons = [];
        if (empty($categories_input)) {
            if (!empty($icons_yml)) {
                foreach ($icons_yml as $k => $v) {
                    $icons = array_merge($icons, $v['icons']);
                }
            }
        } else {
            foreach ($categories_input as $k => $v) {
                if (isset($icons_yml[$v])) {
                    $icons = array_merge($icons, $icons_yml[$v]['icons']);
                }
            }
        }

        $icon_merge = [];
        $icon_types_flip = array_flip(get_icon_types());
        for ($j = 0; $j < count($icons); $j++) {
            for ($i = 0; $i < count($types); $i++) {
                if (isset($icon_types_flip[$types[$i]]) && in_array($icon_types_flip[$types[$i]], $checking_yml[$icons[$j]]['styles'])) {
                    $ic = $types[$i] . ' fa-' . $icons[$j];
                    if (!in_array($ic, $icon_merge))
                        array_push($icon_merge, $ic);
                }
            }
        }

        if (empty($icons)) {
            return [
                'status' => 0,
                'message' => __('Not found icons')
            ];
        } else {
            return [
                'status' => 1,
                'icons' => $icon_merge,
            ];
        }
    }
}