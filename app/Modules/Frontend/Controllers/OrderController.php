<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/21/20
 * Time: 13:00
 */

namespace App\Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = OrderService::inst();
    }

    public function paymentChecking(Request $request)
    {
        $order_token = $request->get('order_token');
        $status = $request->get('status');

        $response = $this->service->paymentChecking($order_token, $status);

        if ($response && ($response['payment_status'] == 1)) {
            return redirect(route('complete-order') . "?" . http_build_query(['order_token' => $order_token]));
        } else {
            return redirect(url('checkout'))->with([
                'message' => $response['message'],
                'order_token' => $order_token,
                'payment_failed' => 1,
            ]);
        }
    }

    public function completeOrder(Request $request)
    {
        $response = $this->service->completeOrderChecking($request);
        $view = apply_filter('gmz_complete_order_view', 'Frontend::page.complete-order', $response);
        return view($view, $response);
    }

    public function checkoutAction(Request $request)
    {
        $response = $this->service->checkOut($request);
        return response()->json($response);
    }

    public function checkoutView()
    {
        $order_data = NULL;

        if (session()->has('payment_failed') && (session('payment_failed') == 1)) {

            $order_token = \session('order_token');
            $order_data = $this->service->unsuccessfulPaymentProcessing($order_token);
        }
        $cart = \Cart::inst()->getCart();

        return view('Frontend::page.checkout')->with('order_data', $order_data);
    }

}