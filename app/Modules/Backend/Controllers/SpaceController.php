<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Services\CommentService;
use App\Services\SpaceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class SpaceController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = SpaceService::inst();
    }

    public function changePostStatusAction(Request $request)
    {
        $response = $this->service->changeStatus($request);
        return response()->json($response);
    }

    public function allReviewView()
    {
        $commentService = CommentService::inst();
        $allPosts = $commentService->getReviewsPagination('space', 5);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('services.space.review'), ['allPosts' => $allPosts]);
    }

    public function hardDeleteSpaceAction(Request $request)
    {
        $response = $this->service->hardDeleteSpace($request);
        return response()->json($response);
    }

    public function restoreSpaceAction(Request $request)
    {
        $response = $this->service->restoreSpace($request);
        return response()->json($response);
    }

    public function deleteSpaceAction(Request $request)
    {
        $response = $this->service->deletePost($request);
        return response()->json($response);
    }

    public function editSpaceView($id)
    {
        $postData = $this->service->storeTermData($id);
        if ($postData) {
            $postData = $postData->getAttributes();
            $postData['post_type'] = 'space';
            return $this->getView($this->getFolderView('services.space.edit'), [
                'serviceData' => $postData,
                'title' => __('Edit space'),
                'new' => false
            ]);
        }
        return response()->redirectTo(dashboard_url('all-space'));
    }

    public function allSpaceView(Request $request)
    {
        $this->service->deletePostTemp();
        $status = $request->get('status', '');
        $where = [];
        $post_status = admin_config('space_status');
        if (!empty($status) && in_array($status, array_keys($post_status))) {
            $where['status'] = $status;
        }
        $allPosts = $this->service->getPostsPagination(10, $where);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('services.space.all'), ['allPosts' => $allPosts]);
    }

    public function saveSpaceAction(Request $request)
    {
        $response = $this->service->savePost($request);
        return response()->json($response);
    }

    public function newSpaceView()
    {
        $this->service->deletePostTemp();
        $id = $this->service->storeNewPost();
        $postData = $this->service->getPostById($id)->getAttributes();
        $postData['post_type'] = 'space';
        return $this->getView($this->getFolderView('services.space.edit'), [
            'serviceData' => $postData,
            'title' => __('Add new space'),
            'new' => true
        ]);
    }
}