<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Services\PageService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class PageController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = PageService::inst();
    }

    public function changePostStatusAction(Request $request)
    {
        $response = $this->service->changeStatus($request);
        return response()->json($response);
    }

    public function deletePageAction(Request $request)
    {
        $response = $this->service->deletePage($request);
        return response()->json($response);
    }

    public function hardDeletePageAction(Request $request)
    {
        $response = $this->service->hardDeletePage($request);
        return response()->json($response);
    }

    public function restorePageAction(Request $request)
    {
        $response = $this->service->restorePage($request);
        return response()->json($response);
    }

    public function editPageView($id)
    {
        $postData = $this->service->getPostById($id);
        if (!is_null($postData)) {
            $postData = $postData->getAttributes();
            $postData['post_type'] = 'page';
            return $this->getView($this->getFolderView('services.page.edit'), [
                'serviceData' => $postData,
                'title' => __('Edit page'),
                'new' => false
            ]);
        }

        return response()->redirectTo(dashboard_url('all-pages'));
    }

    public function allPageView(Request $request)
    {
        $this->service->deletePostTemp();
        $status = $request->get('status', '');
        $where = [];
        $post_status = admin_config('page_status');
        if (!empty($status) && in_array($status, array_keys($post_status))) {
            $where['status'] = $status;
        }
        $allPosts = $this->service->getPostsPagination(10, $where);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('services.page.all'), ['allPosts' => $allPosts]);
    }

    public function savePageAction(Request $request)
    {
        $response = $this->service->savePost($request);
        return response()->json($response);
    }

    public function newPageView()
    {
        $this->service->deletePostTemp();
        $id = $this->service->storeNewPost();
        $postData = $this->service->getPostById($id)->getAttributes();
        $postData['post_type'] = 'page';
        return $this->getView($this->getFolderView('services.page.edit'), [
            'serviceData' => $postData,
            'title' => __('Add new page'),
            'new' => true
        ]);
    }
}