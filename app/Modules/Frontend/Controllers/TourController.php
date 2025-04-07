<?php

namespace App\Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use App\Services\TourService;
use Illuminate\Http\Request;

class TourController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = TourService::inst();
    }

    public function fetchTimeAction(Request $request)
    {
        $response = $this->service->fetchTime($request);
        return response()->json($response);
    }

    public function tourPageView()
    {
        return view('Frontend::services.tour.index');
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
        $check_out = $request->post('check_out', $check_in);
        $extra = $request->post('extras');
        $adult = $request->post('adult');
        $children = $request->post('children');
        $infant = $request->post('infant');
        $response = $this->service->getRealPrice($post_id, $check_in, $check_out, $extra, $adult, $children, $infant);
        return response()->json($response);
    }

    public function fetchAvailabilityAction(Request $request)
    {
        $response = $this->service->fetchTourAvailability($request);
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
                $post['post_type'] = GMZ_SERVICE_TOUR;
                return view('Frontend::services.tour.single', ['post' => $post]);
            }
        }
        return response()->view('Frontend::errors.404', [], 200);
    }

    public function tourSearchAction(Request $request)
    {
        $data = $this->service->getSearchResult($request);
        return response()->json($data);
    }

    public function tourSearchView(Request $request)
    {
        return view('Frontend::services.tour.search');
    }
}