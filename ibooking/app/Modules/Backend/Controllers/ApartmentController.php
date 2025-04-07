<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Services\ApartmentService;
use App\Services\CommentService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class ApartmentController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = ApartmentService::inst();
    }

    public function changePostStatusAction(Request $request)
    {
        $response = $this->service->changeStatus($request);
        return response()->json($response);
    }

    public function allReviewView()
    {
        $commentService = CommentService::inst();
        $allPosts = $commentService->getReviewsPagination('apartment', 5);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('services.apartment.review'), ['allPosts' => $allPosts]);
    }

    public function hardDeleteApartmentAction(Request $request)
    {
        $response = $this->service->hardDeleteApartment($request);
        return response()->json($response);
    }

    public function restoreApartmentAction(Request $request)
    {
        $response = $this->service->restoreApartment($request);
        return response()->json($response);
    }

    public function deleteApartmentAction(Request $request)
    {
        $response = $this->service->deletePost($request);
        return response()->json($response);
    }

    public function editApartmentView($id)
    {
        $postData = $this->service->storeTermData($id);
        if ($postData) {
            $postData = $postData->getAttributes();
            $postData['post_type'] = 'apartment';
            return $this->getView($this->getFolderView('services.apartment.edit'), [
                'serviceData' => $postData,
                'title' => __('Edit apartment'),
                'new' => false
            ]);
        }
        return response()->redirectTo(dashboard_url('all-apartments'));
    }

    public function allApartmentView(Request $request)
    {
        $this->service->deletePostTemp();
        $status = $request->get('status', '');
        $where = [];
        $post_status = admin_config('apartment_status');
        if (!empty($status) && in_array($status, array_keys($post_status))) {
            $where['status'] = $status;
        }
        $allPosts = $this->service->getPostsPagination(10, $where);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('services.apartment.all'), ['allPosts' => $allPosts]);
    }

    public function saveApartmentAction(Request $request)
    {
        $response = $this->service->savePost($request);
        return response()->json($response);
    }

    public function newApartmentView()
    {
        $this->service->deletePostTemp();
        $id = $this->service->storeNewPost();
        $postData = $this->service->getPostById($id)->getAttributes();
        $postData['post_type'] = 'apartment';
        return $this->getView($this->getFolderView('services.apartment.edit'), [
            'serviceData' => $postData,
            'title' => __('Add new apartment'),
            'new' => true
        ]);
    }
}