<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 12/23/2020
 * Time: 7:35 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class OrderController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = OrderService::inst();
    }

    public function getOrderView($post_type, Request $request)
    {
        $result = $this->service->getOrderManagement($post_type, $request);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('order.' . $result['view_name'] . '-order'), ['allPosts' => $result['all_posts']]);
    }

    public function getOrderDetailAction(Request $request)
    {
        $response = $this->service->getOrderDetail($request);
        return response()->json($response);
    }

    public function bookingHistoryView(Request $request)
    {
        $post_type = 'all';
        $my_order = false;
        if (is_partner() || is_customer()) {
            $my_order = true;
        }
        $result = $this->service->getOrderManagement($post_type, $request, $my_order);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('order.history'), ['allPosts' => $result['all_posts']]);
    }

    public function updateStatusOrder(Request $request)
    {
        return $this->service->updateStatusOrder($request);
    }
}