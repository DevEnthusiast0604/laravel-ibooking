<?php

namespace App\Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SpaceService;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = SpaceService::inst();
    }

    public function fetchTimeAction(Request $request)
    {
        $response = $this->service->fetchTime($request);
        return response()->json($response);
    }

    public function spacePageView()
    {
        return view('Frontend::services.space.index');
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
        $response = $this->service->getRealPrice($post_id, $check_in, $check_out, $extra);
        return response()->json($response);
    }

    public function fetchAvailabilityAction(Request $request)
    {
        $response = $this->service->fetchSpaceAvailability($request);
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
                $post['post_type'] = GMZ_SERVICE_SPACE;
                return view('Frontend::services.space.single', ['post' => $post]);
            }
        }
        return response()->view('Frontend::errors.404', [], 200);
    }

    public function spaceSearchAction(Request $request)
    {
        $data = $this->service->getSearchResult($request);
        return response()->json($data);
    }

    public function spaceSearchView(Request $request)
    {
        return view('Frontend::services.space.search');
    }
}