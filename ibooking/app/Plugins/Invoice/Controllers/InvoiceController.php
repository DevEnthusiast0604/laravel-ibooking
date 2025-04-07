<?php

namespace App\Plugins\Invoice\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\Invoice\Models\Order;
use Illuminate\Http\Request;

if (!defined('GMZPATH')) {
    exit;
}

class InvoiceController extends Controller
{
    private static $_inst;

    public function __construct()
    {
    }

    public function getInvoice(Request $request, $orderToken)
    {
        $model = new Order();
        $order = $model->getOrderItem($orderToken);
        if($order) {
            $canView = false;
            if (is_admin()) {
                $canView = true;
            }
            if (is_partner() || is_customer()) {
                $userIDLogged = get_current_user_id();
                if (is_partner() && ($userIDLogged == $order['owner'] || $userIDLogged == $order['buyer'])) {
                    $canView = true;
                }
                if(is_customer() && $userIDLogged == $order['buyer']){
                    $canView = true;
                }
            }
            if($canView) {
                echo view('Plugin.Invoice::index', ['order' => $order]);die;
            }
        }
        return response()->redirectTo('/');
    }

    public static function inst()
    {
        if (empty(self::$_inst)) {
            self::$_inst = new self();
        }
        return self::$_inst;
    }
}