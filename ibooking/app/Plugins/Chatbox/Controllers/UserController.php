<?php

namespace App\Plugins\Chatbox\Controllers;

use App\Repositories\MetaRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

if (!defined('GMZPATH')) {
    exit;
}

if (!class_exists('UserController')) {
    class UserController
    {
        private static $_inst;
        public $load = true;

        public function __construct()
        {
            add_action('gmz_profile_form_after', [$this, '_addProfileFields'], 10, 2);
            add_action('gmz_update_user_info', [$this, '_saveWidgetCode'], 10, 1);
            add_action('gmz_init_footer', [$this, '_addChabox'], 10, 1);
        }

        public function _addChabox()
        {
            global $post;
            $routeName = Route::currentRouteName();
            $userID = false;
            if($post && $post['author']){
                $role = get_user_role($post['author']);
                if(in_array($role, ['admin', 'partner'])) {
                    $userID = $post['author'];
                }
            }
            if($routeName == 'author.view'){
                $userID = \request()->route()->parameter('id');
            }
            if($userID){
                $chatboxCode = get_user_meta($userID, 'chatbox_code');
                if (!empty($chatboxCode)) {
                    echo $chatboxCode;
                }
            }
        }

        public function _saveWidgetCode($data)
        {
            if(is_admin() || is_partner()) {
                $metaRepo = MetaRepository::inst();
                $userID = get_current_user_id();
                $exists = $metaRepo->where([
                    'post_id' => $userID,
                    'post_type' => 'user',
                    'meta_key' => 'chatbox_code'
                ], true);
                if($exists){
                    $metaRepo->updateByWhere([
                        'post_id' => $userID,
                        'post_type' => 'user',
                        'meta_key' => 'chatbox_code'
                    ], ['meta_value' => $data['chatbox_code']]);
                }else {
                    $metaRepo->create([
                        'post_id' => $userID,
                        'post_type' => 'user',
                        'meta_key' => 'chatbox_code',
                        'meta_value' => $data['chatbox_code']
                    ]);
                }
            }
        }

        public function _addProfileFields($data)
        {
            if(is_partner() || is_admin()) {
                $fields = $this->_getPorfileFields();
                $serviceData = get_user_data();
                $serviceData['chatbox_code'] = get_user_meta(get_current_user_id(), 'chatbox_code');
                echo cbc()->view('admin.user.profile', ['fields' => $fields, 'serviceData' => $serviceData]);
            }
        }

        private function _getPorfileFields()
        {
            return [
                [
                    'id' => 'chatbox_code',
                    'label' => ilangs('Tawk.to Widget Code'),
                    'type' => 'textarea',
                    'std' => '',
                    'break' => true
                ]
            ];
        }

        public static function inst()
        {
            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }
            return self::$_inst;
        }
    }
}