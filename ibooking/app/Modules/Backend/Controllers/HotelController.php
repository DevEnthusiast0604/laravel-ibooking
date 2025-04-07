<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Services\CommentService;
use App\Services\HotelService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class HotelController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = HotelService::inst();
    }

    public function changePostStatusAction(Request $request)
    {
        $response = $this->service->changeStatus($request);
        return response()->json($response);
    }

    public function allReviewView()
    {
        $commentService = CommentService::inst();
        $allPosts = $commentService->getReviewsPagination(GMZ_SERVICE_HOTEL, 5);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('services.hotel.review'), ['allPosts' => $allPosts]);
    }

    public function hardDeleteHotelAction(Request $request)
    {
        $response = $this->service->hardDeleteHotel($request);
        return response()->json($response);
    }

    public function restoreHotelAction(Request $request)
    {
        $response = $this->service->restoreHotel($request);
        return response()->json($response);
    }

    public function deleteHotelAction(Request $request)
    {
        $response = $this->service->deletePost($request);
        return response()->json($response);
    }

    public function editHotelView($id)
    {
        $postData = $this->service->storeTermData($id);
        if ($postData) {
            $postData = $postData->getAttributes();
            $postData['post_type'] = GMZ_SERVICE_HOTEL;
            return $this->getView($this->getFolderView('services.hotel.edit'), [
                'serviceData' => $postData,
                'title' => __('Edit hotel'),
                'new' => false
            ]);
        }
        return response()->redirectTo(dashboard_url('all-hotels'));
    }

    public function allHotelView(Request $request)
    {
        $this->service->deletePostTemp();
        $status = $request->get('status', '');
        $where = [];
        $post_status = admin_config('hotel_status');
        if (!empty($status) && in_array($status, array_keys($post_status))) {
            $where['status'] = $status;
        }
        $allPosts = $this->service->getPostsPagination(10, $where);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('services.hotel.all'), ['allPosts' => $allPosts]);
    }

    public function saveHotelAction(Request $request)
    {
        $response = $this->service->savePost($request);
        return response()->json($response);
    }

    public function newHotelView()
    {
        $this->service->deletePostTemp();
        $id = $this->service->storeNewPost();
        $postData = $this->service->getPostById($id)->getAttributes();
        $postData['post_type'] = GMZ_SERVICE_HOTEL;
        return $this->getView($this->getFolderView('services.hotel.edit'), [
            'serviceData' => $postData,
            'title' => __('Add new hotel'),
            'new' => true
        ]);
    }
}