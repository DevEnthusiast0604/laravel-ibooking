<?php

namespace App\Services;

use App\Repositories\WishlistRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WishlistService extends AbstractService
{
    private static $_inst;
    protected $repository;

    public static function inst()
    {
        if (empty(self::$_inst)) {
            self::$_inst = new self();
        }
        return self::$_inst;
    }

    public function __construct()
    {
        $this->repository = WishlistRepository::inst();
    }

    public function addWishlist(Request $request)
    {
        $post_id = $request->post('post_id');
        $post_type = $request->post('post_type');
        if ($post_id && $post_type) {
            $user_id = get_current_user_id();

            $check_exists = $this->repository->findOneBy([
                'post_id' => $post_id,
                'post_type' => $post_type,
                'author' => $user_id
            ]);

            $cache_key = 'wishlist' . $user_id . $post_type;
            Cache::forget($cache_key);

            if ($check_exists) {
                $this->repository->deleteByWhere([
                    'post_id' => $post_id,
                    'post_type' => $post_type,
                    'author' => $user_id
                ]);
                return [
                    'status' => true,
                    'html' => add_wishlist_box($post_id, $post_type, false),
                    'action' => 'remove'
                ];
            } else {
                $inserted = $this->repository->create([
                    'post_id' => $request->post('post_id'),
                    'post_type' => $request->post('post_type'),
                    'author' => $user_id
                ]);
                if ($inserted) {
                    return [
                        'status' => true,
                        'html' => add_wishlist_box($post_id, $post_type, false),
                        'action' => 'add'
                    ];
                }
            }
        }
        return [
            'status' => false
        ];
    }
}