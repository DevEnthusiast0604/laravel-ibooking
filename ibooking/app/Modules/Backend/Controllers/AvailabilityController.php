<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 12/5/2020
 * Time: 5:27 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Services\AvailabilityService;
use Illuminate\Http\Request;

class AvailabilityController
{
    private $service;

    public function __construct()
    {
        $this->service = AvailabilityService::inst();
    }

    public function addAvailability(Request $request)
    {
        $post_type = $request->post('calendar_post_type', GMZ_SERVICE_CAR);
        switch ($post_type) {
            case GMZ_SERVICE_CAR:
            default:
                $response = $this->service->addCarAvailability($request);
                break;
            case GMZ_SERVICE_APARTMENT:
                $response = $this->service->addApartmentAvailability($request);
                break;
            case GMZ_SERVICE_TOUR:
                $response = $this->service->addTourAvailability($request);
                break;
            case GMZ_SERVICE_SPACE:
                $response = $this->service->addSpaceAvailability($request);
                break;
            case GMZ_SERVICE_ROOM:
                $response = $this->service->addRoomAvailability($request);
                break;
            case GMZ_SERVICE_AGENT:
                $response = $this->service->addAgentAvailability($request);
                break;

            case GMZ_SERVICE_BEAUTY:
                $response = $this->service->addbeautyAvailability($request);
                break;
        }
        return response()->json($response);
    }

    public function getAvailability(Request $request)
    {
        $post_type = $request->post('post_type', GMZ_SERVICE_APARTMENT);
        switch ($post_type) {
            case GMZ_SERVICE_CAR:
            default:
                $response = $this->service->getCarAvailability($request);
                break;
            case GMZ_SERVICE_APARTMENT:
                $response = $this->service->getApartmentAvailability($request);
                break;
            case GMZ_SERVICE_TOUR:
                $response = $this->service->getTourAvailability($request);
                break;
            case GMZ_SERVICE_SPACE:
                $response = $this->service->getSpaceAvailability($request);
                break;
            case GMZ_SERVICE_ROOM:
                $response = $this->service->getRoomAvailability($request);
                break;
            case GMZ_SERVICE_AGENT:
                $response = $this->service->getAgentAvailability($request);
                break;
            case GMZ_SERVICE_BEAUTY:
                $response = $this->service->getBeautyAvailability($request);
                break;
        }
        return response()->json($response);
    }
}