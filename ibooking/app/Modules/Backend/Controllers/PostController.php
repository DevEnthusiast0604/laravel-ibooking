<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Services\CommentService;
use App\Services\PostService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class PostController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = PostService::inst();
    }

    public function changePostStatusAction(Request $request)
    {
        $response = $this->service->changeStatus($request);
        return response()->json($response);
    }

    public function allCommentView()
    {
        $commentService = CommentService::inst();
        $allPosts = $commentService->getReviewsPagination('post', 5);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('services.post.comment'), ['allPosts' => $allPosts]);
    }

    public function deletePostAction(Request $request)
    {
        $response = $this->service->deletePost($request);
        return response()->json($response);
    }

    public function hardDeletePostAction(Request $request)
    {
        $response = $this->service->hardDeletePost($request);
        return response()->json($response);
    }

    public function restorePostAction(Request $request)
    {
        $response = $this->service->restorePost($request);
        return response()->json($response);
    }

    public function editPostView($id)
    {
        $postData = $this->service->storeTermData($id);
        if ($postData) {
            $postData = $postData->getAttributes();
            $postData['post_type'] = 'post';
            return $this->getView($this->getFolderView('services.post.edit'), [
                'serviceData' => $postData,
                'title' => __('Edit post'),
                'new' => false
            ]);
        }
        return response()->redirectTo(dashboard_url('all-posts'));
    }

    public function allPostView(Request $request)
    {
        $this->service->deletePostTemp();
        $status = $request->get('status', '');
        $where = [];
        $post_status = admin_config('post_status');
        if (!empty($status) && in_array($status, array_keys($post_status))) {
            $where['status'] = $status;
        }
        $allPosts = $this->service->getPostsPagination(10, $where);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('services.post.all'), ['allPosts' => $allPosts]);
    }

    public function savePostAction(Request $request)
    {
        $response = $this->service->savePost($request);
        return response()->json($response);
    }

    public function newPostView()
    {
        $this->service->deletePostTemp();
        $id = $this->service->storeNewPost();
        $postData = $this->service->getPostById($id)->getAttributes();
        $postData['post_type'] = 'post';
        return $this->getView($this->getFolderView('services.post.edit'), [
            'serviceData' => $postData,
            'title' => __('Add new post'),
            'new' => true
        ]);
    }
}