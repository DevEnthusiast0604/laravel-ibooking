<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/8/20
 * Time: 17:37
 */

namespace App\Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PageService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = PageService::inst();
    }

    public function contactUsAction(Request $request)
    {
        $response = $this->service->sendContact($request);
        return response()->json($response);
    }

    public function contactUsView()
    {
        return view('Frontend::page.contact-us');
    }

    public function getBecomePartnerPage()
    {
        return view('Frontend::page.become-a-partner');
    }

    public function singleView($slug)
    {
        $data = $this->service->getPostBySlug($slug);
        if ($data) {
            if (is_admin() || $data['author'] == get_current_user_id() || $data['status'] == 'publish') {
                global $post;
                $post = $data->getAttributes();
                $post['post_type'] = 'page';
                return view('Frontend::services.page.single', ['post' => $post]);
            }
        }
        return response()->view('Frontend::errors.404', [], 200);
    }

    public function getHomePage()
    {
        return view('Frontend::page.home');
    }
}