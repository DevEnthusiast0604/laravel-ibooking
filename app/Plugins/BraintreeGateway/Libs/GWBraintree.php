<?php
if ( ! defined( 'GMZPATH' ) ) { exit; }
use App\Plugins\BraintreeGateway\Controllers\Braintree;
use TorMorten\Eventy\Facades\Events as Eventy;
if(!class_exists('GWBraintree')) {
    class GWBraintree
    {
        private static $_inst;
        public $pluginPath;

        public function __construct()
        {
            $this->pluginPath = dirname(__DIR__);
            Eventy::addFilter('gmz_gateways', [$this, '_addGateway'], 20, 1);
        }

        public function _addGateway($gateways)
        {
            $obj = new Braintree();
            $gateways[$obj->getID()] = $obj;
            return $gateways;
        }

        public static function inst()
        {
            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }
            return self::$_inst;
        }
    }

    GWBraintree::inst();
}