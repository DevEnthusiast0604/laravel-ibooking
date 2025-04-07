<?php

namespace App\Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BeautyService;
use Illuminate\Http\Request;

class BeautyController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = BeautyService::inst();
    }

    public function fetchTimeAction(Request $request)
    {
        $response = $this->service->fetchTime($request);
        return response()->json($response);
    }

    public function beautyPageView()
    {
        return view('Frontend::services.beauty.index');
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
        $response = $this->service->fetchBeautyAvailability($request);
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
            $data = $data->toArray();
            if (is_admin() || $data['author'] == get_current_user_id() || $data['status'] == 'publish') {
                global $post;
                $post = $data;
                $post['post_type'] = GMZ_SERVICE_BEAUTY;
                return view('Frontend::services.beauty.single', ['post' => $post]);
            }
        }
        return response()->view('Frontend::errors.404', [], 200);
    }

    public function getBookingForm(Request $request)
    {
        $checkIn = $request->get('checkIn');
        $slug = $request->get('slug');

        if (empty($checkIn) || empty($slug)) {
            exit("Empty checkIn or Slug");
        }

        $postData = $this->service->getPostBySlug($slug);
        if (!$postData) {
            exit("Empty post data");
        }

        if ($checkIn < strtotime('today midnight')) {
            exit("Date not found");
        }

        $id = $postData['id'];
        $data = $this->service->getSlotEmptyByDay($id, $checkIn);
        $custom_price = $this->service->getCustomPriceByDay($id, $checkIn);

        $html = view('Frontend::services.beauty.single.booking-form-content', ['data' => $data, 'post' => $postData])->render();

        return response()->json([
            'status' => true,
            'html' => $html,
            'price' => $custom_price,
        ]);

    }

    public function beautySearchAction(Request $request)
    {
        $data = $this->service->getSearchResult($request);
        return response()->json($data);
    }

    public function beautySearchView(Request $request)
    {
        return view('Frontend::services.beauty.search');
    }
}