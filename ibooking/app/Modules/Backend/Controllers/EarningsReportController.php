<?php

namespace App\Modules\Backend\Controllers;

use App\Http\Controllers\Controller;
use App\Services\EarningsReportService;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class EarningsReportController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = EarningsReportService::inst();
    }

    public function analyticsView($id = null)
    {
        if (empty($id)) {
            if (is_admin()) {
                $id = -1;
            } else {
                $id = get_current_user_id();
            }
        }
        return $this->getView($this->getFolderView('earnings.analytics'), ['userID' => $id]);
    }

    public function partnerEarningsView()
    {
        $data = $this->service->getEarningsData();
        Paginator::useBootstrap();
        return $this->getView($this->getFolderView('earnings.partner-earnings'), ['data' => $data]);
    }

    public function getWidget(Request $request)
    {
        $response = $this->service->getWidget($request);
        if ($response) {
            $widget = $response['widget'];
            $data = $response['data'];
            $view_part = 'widget.' . $widget;
            return view($this->getFolderView($view_part), ['data' => $data]);
        }
        return false;
    }
}