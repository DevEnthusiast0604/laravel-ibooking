<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Services\CarService;
use App\Services\CommentService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class CarController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = CarService::inst();
    }

    public function changePostStatusAction(Request $request)
    {
        $response = $this->service->changeStatus($request);
        return response()->json($response);
    }

    public function allReviewView()
    {
        $commentService = CommentService::inst();
        $allPosts = $commentService->getReviewsPagination('car', 5);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('services.car.review'), ['allPosts' => $allPosts]);
    }

    public function hardDeleteCarAction(Request $request)
    {
        $response = $this->service->hardDeleteCar($request);
        return response()->json($response);
    }

    public function restoreCarAction(Request $request)
    {
        $response = $this->service->restoreCar($request);
        return response()->json($response);
    }

    public function deleteCarAction(Request $request)
    {
        $response = $this->service->deletePost($request);
        return response()->json($response);
    }

    public function editCarView($id)
    {
        $postData = $this->service->storeTermData($id);
        if ($postData) {
            $postData = $postData->getAttributes();
            $postData['post_type'] = 'car';
            return $this->getView($this->getFolderView('services.car.edit'), [
                'serviceData' => $postData,
                'title' => __('Edit car'),
                'new' => false
            ]);
        }
        return response()->redirectTo(dashboard_url('all-cars'));
    }

    public function allCarView(Request $request)
    {
        $this->service->deletePostTemp();
        $status = $request->get('status', '');
        $where = [];
        $post_status = admin_config('car_status');
        if (!empty($status) && in_array($status, array_keys($post_status))) {
            $where['status'] = $status;
        }
        $allPosts = $this->service->getPostsPagination(10, $where);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('services.car.all'), ['allPosts' => $allPosts]);
    }

    public function saveCarAction(Request $request)
    {
        $response = $this->service->savePost($request);
        return response()->json($response);
    }

    public function newCarView()
    {
        $this->service->deletePostTemp();
        $id = $this->service->storeNewPost();
        $postData = $this->service->getPostById($id)->getAttributes();
        $postData['post_type'] = 'car';
        return $this->getView($this->getFolderView('services.car.edit'), [
            'serviceData' => $postData,
            'title' => __('Add new car'),
            'new' => true
        ]);
    }
}