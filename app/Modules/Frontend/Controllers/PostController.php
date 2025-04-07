<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/8/20
 * Time: 17:37
 */

namespace App\Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PostService;
use Illuminate\Pagination\Paginator;

class PostController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = PostService::inst();
    }

    public function tagView($tag_slug)
    {
        $term = get_term('name', $tag_slug);
        if ($term) {
            global $tag;
            $tag = $term;
            $posts = $this->service->getArchivePagination(10, [
                'tag_id' => $term->id
            ]);
            $title = get_translate($term->term_title);
            return view('Frontend::services.post.archive', ['title' => $title, 'type' => 'tag', 'posts' => $posts]);
        }
        return response()->view('Frontend::errors.404', [], 200);
    }

    public function categoryView($category_slug)
    {
        $term = get_term('name', $category_slug);
        if ($term) {
            global $category;
            $category = $term;
            $posts = $this->service->getArchivePagination(10, [
                'category_id' => $term->id
            ]);
            $title = get_translate($term->term_title);
            Paginator::useBootstrap();
            return view('Frontend::services.post.archive', ['title' => $title, 'type' => 'category', 'posts' => $posts]);
        }
        return response()->view('Frontend::errors.404', [], 200);
    }

    public function blogView()
    {
        $posts = $this->service->getPostsPagination(10);
        Paginator::useBootstrap();
        $title = __('Blog');
        return view('Frontend::services.post.archive', ['title' => $title, 'posts' => $posts]);
    }

    public function singleView($slug)
    {
        $data = $this->service->getPostBySlug($slug);
        if ($data) {
            if (is_admin() || $data['author'] == get_current_user_id() || $data['status'] == 'publish') {
                global $post;
                $post = $data->getAttributes();
                $post['post_type'] = 'post';
                return view('Frontend::services.post.single', ['post' => $post]);
            }
        }
        return response()->view('Frontend::errors.404', [], 200);
    }
}