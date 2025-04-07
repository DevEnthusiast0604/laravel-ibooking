<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Services\CouponService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use TorMorten\Eventy\Facades\Eventy;

class CouponController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = CouponService::inst();
    }

    public function changeCouponStatusAction(Request $request)
    {
        $reponse = $this->service->changeStatus($request);
        return response()->json($reponse);
    }

    public function couponView()
    {
        $where = Eventy::filter('gmz_all_coupon_filter', []);
        $allCoupons = $this->service->getPostsPagination(10, $where);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('coupon.all'), ['allCoupons' => $allCoupons]);
    }

    public function newCouponAction(Request $request)
    {
        $reponse = $this->service->newCoupon($request);
        return response()->json($reponse);
    }

    public function getCouponFormAction(Request $request)
    {
        $response = $this->service->getCouponForm($request);
        return response()->json($response);
    }

    public function editCouponAction(Request $request)
    {
        $response = $this->service->editCoupon($request);
        return response()->json($response);
    }

    public function deleteCouponAction(Request $request)
    {
        $response = $this->service->deleteCoupon($request);
        return response()->json($response);
    }
}