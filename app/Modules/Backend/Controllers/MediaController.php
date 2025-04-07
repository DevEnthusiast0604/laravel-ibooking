<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Services\MediaService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class MediaController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = MediaService::inst();
    }

    public function bulkDeleteMediaAction(Request $request)
    {
        $response = $this->service->bulkDeleteMediaItem($request);
        return response()->json($response);
    }

    public function deleteMediaAction(Request $request)
    {
        $response = $this->service->deleteMediaItem($request);
        return response()->json($response);
    }

    public function getMediaDetailAction(Request $request)
    {
        $response = $this->service->getMediaDetail($request);
        return response()->json($response);
    }

    public function getAllMediaAction(Request $request)
    {
        $response = $this->service->getMediaPopup($request);
        return response()->json($response);
    }

    public function allMediaView(Request $request)
    {
        $data = $this->service->getAllMedia($request);
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('media.all'), ['data' => $data]);
    }

    public function uploadImageAction(Request $request)
    {
        $response = $this->service->uploadMedia($request);
        return response()->json($response);
    }
}