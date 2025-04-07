<?php

namespace App\Modules\Backend\Controllers;

use App\Services\OptionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OptionController extends Controller
{
    private $service;
    private $optionName = 'gmz_options';

    public function __construct()
    {
        $this->service = OptionService::inst();
    }

    public function getCheckingEmailFormAction(Request $request)
    {
        $response = $this->service->getCheckingEmailForm($request);
        return response()->json($response);
    }

    public function sortPaymentAction(Request $request)
    {
        $response = $this->service->sortPayment($request);
        return response()->json($response);
    }

    public function getPaymentFormAction(Request $request)
    {
        $response = $this->service->getPaymentForm($request);
        return response()->json($response);
    }

    public function getIconsAction(Request $request)
    {
        $response = $this->service->getIconsAction($request);
        return response()->json($response);
    }

    public function settingsView(Request $request)
    {
        if (is_admin()) {
            $settings_db = $this->service->getOption($this->optionName, true);
            return $this->getView('Backend::settings.index', ['settings_db' => $settings_db]);
        }
        return response()->redirectTo('dashboard');
    }

    public function saveSettingsAction(Request $request)
    {
        $response = $this->service->saveSettings($request);
        return response()->json($response);
    }

    public function getListItemHtml(Request $request)
    {
        $response = $this->service->getListItemHtml($request);
        return response()->json($response);
    }
}