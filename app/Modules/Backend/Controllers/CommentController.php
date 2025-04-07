<?php

namespace App\Modules\Backend\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = CommentService::inst();
    }

    public function deleteReviewAction(Request $request)
    {
        $response = $this->service->deleteItem($request);
        return response()->json($response);
    }

    public function changeReviewStatusAction(Request $request)
    {
        $response = $this->service->changeStatus($request);
        return response()->json($response);
    }
}