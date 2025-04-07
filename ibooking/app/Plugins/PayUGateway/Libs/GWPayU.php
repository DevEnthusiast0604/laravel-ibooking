<?php
if ( ! defined( 'GMZPATH' ) ) { exit; }
use App\Plugins\PayUGateway\Controllers\PayU;
use TorMorten\Eventy\Facades\Events as Eventy;

if(!class_exists('GWPayU')) {
    class GWPayU
    {
        private static $_inst;

        public function __construct()
        {
            Eventy::addFilter('gmz_gateways', [$this, '_addGateway'], 20, 1);
        }

        public function _addGateway($gateways)
        {
            $obj = new PayU();
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

    GWPayU::inst();
}