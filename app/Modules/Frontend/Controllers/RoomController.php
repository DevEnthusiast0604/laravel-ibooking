<?php

namespace App\Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use App\Services\RoomService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = RoomService::inst();
    }

    public function getRealPriceAction(Request $request)
    {
        $response = $this->service->roomRealPrice($request);
        return response()->json($response);
    }

    public function roomDetailAction(Request $request)
    {
        $response = $this->service->roomDetail($request);
        return response()->json($response);
    }

    public function searchRoomAction(Request $request)
    {
        $response = $this->service->searchRoom($request);
        return response()->json($response);
    }
}