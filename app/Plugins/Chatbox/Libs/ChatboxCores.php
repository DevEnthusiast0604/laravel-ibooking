<?php
if (!defined('GMZPATH')) {
    exit;
}

if (!class_exists('ChatboxCores')) {
    class ChatboxCores
    {
        private static $_inst;
        public $_assetUrl;
        private $_pluginName = '';

        public function __construct()
        {
            $this->_pluginName = basename(dirname(__DIR__));
            $this->_assetUrl = 'plugins/'. strtolower($this->_pluginName) .'/';
        }

        public function view($view, $data = [])
        {
            return view('Plugin.'. $this->_pluginName .'::' . $view, $data);
        }

        public static function inst()
        {
            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }
            return self::$_inst;
        }
    }

    ChatboxCores::inst();
}