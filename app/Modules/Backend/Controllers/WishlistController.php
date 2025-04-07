<?php

namespace App\Modules\Backend\Controllers;

use App\Services\WishlistService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class WishlistController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = WishlistService::inst();
    }

    public function wishlistAllView()
    {
        $services = get_services_enabled();
        if (!empty($services)) {
            return response()->redirectTo(dashboard_url('wishlist/' . $services[0]));
        }
        return $this->getView($this->getFolderView('wishlist.all'));
    }

    public function wishlistView($post_type, Request $request)
    {
        $services = get_services_enabled();
        if (in_array($post_type, $services)) {
            $wishlist = list_wishlist($post_type);
            $postTypeService = 'App\\Services\\' . ucfirst($post_type) . 'Service';
            $data = $postTypeService::inst()->getWishList(6, $wishlist);
            Paginator::useBootstrap();
            return $this->getView($this->getFolderView('wishlist.index'), ['posts' => $data, 'post_type' => $post_type]);
        } else {
            if (!empty($services)) {
                return response()->redirectTo(dashboard_url('wishlist/' . $services[0]));
            }
            return $this->getView($this->getFolderView('wishlist.all'));
        }
    }
}