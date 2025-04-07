<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/14/2020
 * Time: 8:27 PM
 */
if(!function_exists('get_actived_theme')){
    function get_actived_theme(){
        return get_opt('current_theme', '', false);
    }
}

if(!function_exists('get_actived_plugins')){
    function get_actived_plugins(){
        return [
            'authorizenetgateway',
            'bluesnapgateway',
            'braintreegateway',
            'chatbox',
            'ical',
            'invoice',
            'payugateway',
            'securionpaygateway',
            'skrillgateway',
            'submitformgateway'
            ];
        //return get_opt('current_plugins', []);
    }
}

if(!function_exists('get_icon_types')){
	function get_icon_types(){
		$data = [
			'solid' => 'fas',
			'regular' => 'far',
			'light' => 'fal',
			'brands' => 'fab'
		];
		return $data;
	}
}

if(!function_exists('get_icon_categories')){
    function get_icon_categories(){
        $data = Symfony\Component\Yaml\Yaml::parseFile(public_path('html/assets/vendor/font-awesome-5/categories.yml'));
        $res = [];
        if(!empty($data)){
            foreach ($data as $k => $v){
                $res[$k] = $v['label'];
            }
        }
        return $res;
    }
}

if(!function_exists('update_opt')) {
	function update_opt( $key, $value ) {
		$check_exists = App\Models\Option::query()->where( 'name', $key )->first();
		if ( ! is_null( $check_exists ) && ! empty( $check_exists ) ) {
			App\Models\Option::query()->where( 'name', $key )->update( [
				'value' => $value
			] );
		} else {
			set_opt( $key, $value );
		}
	}
}

if(!function_exists('set_opt')) {
	function set_opt( $key, $value ) {
		App\Models\Option::query()->create( [
			'name'  => $key,
			'value' => $value
		] );
	}
}

if(!function_exists('get_opt')) {
	function get_opt( $key = '', $default = '', $decode = true ) {
		$data = App\Models\Option::query()->where( 'name', $key )->first();
		if ( $data ) {
			$value = $data['value'];
			if ( $decode ) {
				return json_decode( $value, true );
			} else {
				return $value;
			}
		}

		return $default;
	}
}

if(!function_exists('remove_opt')) {
	function remove_opt( $key ) {
		App\Models\Option::query()->where( [ 'name' => $key ] )->delete();
	}
}

if(!function_exists('get_option')) {
	function get_option( $key = '', $default = '', $full = false ) {
        $settings_db = \App\Repositories\OptionRepository::inst()->getOption('gmz_options');
		if ( ! empty( $settings_db ) ) {
			$settings = maybe_unserialize( $settings_db['value'] );
			if ( $full ) {
				return $settings;
			} else {
				if ( isset( $settings[ $key ] ) && ! empty( $settings[ $key ] ) ) {
					return $settings[ $key ];
				}
			}
		} else {
			if ( $full ) {
				return - 1;
			}
		}

		return $default;
	}
}

if(!function_exists('merge_option_values')) {
	function merge_option_values( $settings_db, $field ) {
		if ( empty( $settings_db ) || $settings_db == - 1 ) {
			$settings_db = [];
		}

		$field = array_merge( get_option_default_fields(), $field );

		if ( isset( $settings_db[ $field['id'] ] ) ) {
			$field['value'] = $settings_db[ $field['id'] ];
		} else {
			$field['value'] = '';
		}

		return $field;
	}
}

if(!function_exists('get_option_default_fields')) {
	function get_option_default_fields() {
		return [
			'id'              => 'field_id',
			'label'           => 'Field Label',
			'type'            => 'text',
			'layout'          => 'col-12',
			'std'             => '',
			'break'           => false,
			'translation'     => false,
			'translation_ext' => false,
			'value'           => '',
			'validation'      => '',
			'no_option'       => false,
			'condition'       => '',
			'binding'         => 'title',
			'description'     => '',
		];
	}
}

if(!function_exists('get_config_settings')){
    function get_config_settings(){
        $settings = Eventy::filter('gmz_settings', admin_config('settings', 'settings'));
        return $settings;
    }
}