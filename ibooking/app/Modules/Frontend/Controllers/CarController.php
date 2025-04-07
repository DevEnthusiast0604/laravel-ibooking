<?php

namespace App\Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CarService;
use Illuminate\Http\Request;

class CarController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = CarService::inst();
    }

    public function carPageView()
    {
        return view('Frontend::services.car.index');
    }

    public function sendEnquiryAction(Request $request)
    {
        $response = $this->service->sendEnquiry($request);
        return response()->json($response);
    }

    public function getRealPriceAction(Request $request)
    {
        $post_id = $request->post('post_id');
        $check_in = $request->post('check_in');
        $check_out = $request->post('check_out');
        $number = $request->post('number');
        $equipment = $request->post('equipment');
        $insurance = $request->post('insurance');
        $response = $this->service->getRealPrice($post_id, $check_in, $check_out, $number, $equipment, $insurance);
        return response()->json($response);
    }

    public function fetchAvailabilityAction(Request $request)
    {
        $response = $this->service->fetchCarAvailability($request);
        return response()->json($response);
    }

    public function addCartAction(Request $request)
    {
        $data = $this->service->addToCart($request);
        return response()->json($data);
    }

    public function singleView($slug, Request $request)
    {
        $data = $this->service->getPostBySlug($slug);
        if ($data) {
            if (is_admin() || $data['author'] == get_current_user_id() || $data['status'] == 'publish') {
                global $post;
                $post = $data->getAttributes();
                $post['post_type'] = GMZ_SERVICE_CAR;
                return view('Frontend::services.car.single', ['post' => $post]);
            }
        }
        return response()->view('Frontend::errors.404', [], 200);
    }

    public function carSearchAction(Request $request)
    {
        $data = $this->service->getSearchResult($request);
        return response()->json($data);
    }

    public function carSearchView(Request $request)
    {
        return view('Frontend::services.car.search');
    }
}