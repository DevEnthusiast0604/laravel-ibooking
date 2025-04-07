<?php
namespace App\Plugins\AuthorizeNetGateway\Libs;
use App\Plugins\AuthorizeNetGateway\Controllers\AuthorizeNet;
use TorMorten\Eventy\Facades\Events as Eventy;
class Gateway
{
    private static $_inst;
    public $pluginPath;

    public function __construct(){
        $this->pluginPath = dirname(__DIR__);
        Eventy::addFilter('gmz_gateways', [$this, '_addGateway'], 20, 1);
    }

    public function _addGateway($gateways){
        $obj = new AuthorizeNet();
        $gateways[$obj->getID()] = $obj;
        return $gateways;
    }

    public static function inst(){
        if(empty(self::$_inst)){
            self::$_inst = new self();
        }
        return self::$_inst;
    }
}

Gateway::inst();