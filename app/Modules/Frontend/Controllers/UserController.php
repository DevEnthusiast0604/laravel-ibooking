<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Frontend\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class UserController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = UserService::inst();
    }

    public function authorView($id, Request $request)
    {
        $data = $this->service->getUserData($id);
        if($data){
            $role = get_user_role($id);
            if (in_array($role, ['admin', 'partner'])) {
                $services = get_services_enabled(true);
                $serviceActive = $request->get('service', '');
                if(!empty($services)){
                    if (empty($serviceActive)){
                        $serviceActive = array_keys($services)[0];
                    }
                }
                $posts = [];
                if(!empty($serviceActive)){
                    $repoName = '\\App\\Repositories\\' . ucfirst($serviceActive) . 'Repository';
                    $serviceRepo = $repoName::inst();
                    $posts = $serviceRepo->paginate(6, ['author' => $id], true);
                    Paginator::useBootstrap();
                }
                return view('Frontend::user.index', ['data' => $data, 'services' => $services, 'serviceActive' => $serviceActive, 'posts' => $posts]);
            }
        }
        return response()->view('Frontend::errors.404', [], 200);
    }

    public function loginView(Request $request)
    {
        return view('Frontend::user.login');
    }

    public function becomeAPartnerAction(Request $request)
    {
        $response = $this->service->partnerRegister($request);
        return response()->json($response);
    }

    public function registerView(Request $request)
    {
        return view('Frontend::user.register');
    }

    public function showLinkRequestForm(Request $request)
    {
        return view('Frontend::user.request-form-password');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('Frontend::user.reset-form-password', ['token' => $token, 'email' => $request->get('email')]);
    }
}