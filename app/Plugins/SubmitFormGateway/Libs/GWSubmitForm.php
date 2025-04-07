<?php
namespace App\Plugins\SubmitFormGateway\Libs;
if ( ! defined( 'GMZPATH' ) ) { exit; }
use App\Plugins\SubmitFormGateway\Controllers\SubmitForm;
use TorMorten\Eventy\Facades\Events as Eventy;
class GWSubmitForm
{
    private static $_inst;

    public function __construct(){
        Eventy::addFilter('gmz_gateways', [$this, '_addGateway'], 20, 1);
    }

    public function _addGateway($gateways){
        $obj = new SubmitForm();
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

GWSubmitForm::inst();