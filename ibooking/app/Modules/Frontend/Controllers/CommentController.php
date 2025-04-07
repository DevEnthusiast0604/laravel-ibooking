<?php

namespace App\Modules\Frontend\Controllers;

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

    public function addCommentAction(Request $request)
    {
        $response = $this->service->addComment($request);
        return response()->json($response);
    }
}