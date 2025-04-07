<?php

namespace App\Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class SeoController extends Controller
{
    public function robotsView()
    {
        $seo_enable = get_opt('seo_enable', 'off', false);
        if ($seo_enable != 'on') {
            return response()->redirectTo('/');
        } else {
            $response = response()->view('Frontend::seo.robots')->header('Content-Type', 'text/plain');
            $response->header('Content-Length', strlen($response->getOriginalContent()));
            return $response;
        }
    }

    public function createSitemap()
    {
        $seo_enable = get_opt('seo_enable', 'off', false);
        $seo_sitemap_enable = get_opt('seo_enable_sitemap', 'off', false);
        if ($seo_enable != 'on' || $seo_sitemap_enable != 'on') {
            return response()->redirectTo('/');
        } else {
            $sitemap = App::make('sitemap', ['Content-Type' => 'application/xml']);

            //$sitemap->setCache('laravel.sitemap', 3600);

            $posts_per_page = admin_config('posts_per_page', 'seo');

            $services = admin_config('services', 'seo');

            foreach ($services as $service) {
                $enable = 'on';
                $service_name = $service['id'];
                if ($service['check_enable']) {
                    $enable = get_option($service_name . '_enable', 'on');
                }
                if ($enable == 'on') {
                    $count = DB::table('gmz_' . $service_name)->where('status', 'publish')->count();

                    if ($count > $posts_per_page) {
                        $max_page = (int)ceil($count / $posts_per_page);
                        for ($i = 1; $i <= $max_page; $i++) {
                            $sitemap->add(url('sitemap-' . $service_name . '-' . $i . '.xml'), date(DATE_ISO8601, strtotime(date('Y-m-d H:i:s'))), '1.0', 'daily');
                        }
                    } else {
                        $sitemap->add(url('sitemap-' . $service_name . '.xml'), date(DATE_ISO8601, strtotime(date('Y-m-d H:i:s'))), '1.0', 'daily');
                    }
                }
            }

            //Dynamic content
            $sitemap->add(url('sitemap-category.xml'), date(DATE_ISO8601, strtotime(date('Y-m-d H:i:s'))), '1.0', 'daily');
            $sitemap->add(url('sitemap-tag.xml'), date(DATE_ISO8601, strtotime(date('Y-m-d H:i:s'))), '1.0', 'daily');

            //static page
            $static_pages = admin_config('page', 'seo');
            foreach ($static_pages['items'] as $page) {
                $enable = 'on';
                if (isset($page['check_enable']) && !empty($page['check_enable'])) {
                    $enable = get_option($page['check_enable'] . '_enable', 'on');
                }
                if ($enable == 'on') {
                    $sitemap->add(url($page['route']), date(DATE_ISO8601, strtotime(date('Y-m-d H:i:s'))), '1.0', 'daily');
                }
            }

            return $sitemap->render('xml');
        }
    }

    public function createSitemapService($_service = 'post')
    {
        $seo_enable = get_opt('seo_enable', 'off', false);
        $seo_sitemap_enable = get_opt('seo_enable_sitemap', 'off', false);
        if ($seo_enable != 'on' || $seo_sitemap_enable != 'on') {
            return response()->redirectTo('/');
        } else {
            $sitemap = App::make('sitemap');
            if (in_array($_service, ['category', 'tag'])) {
                $terms = get_terms('name', 'post-' . $_service, 'full');
                if (!$terms->isEmpty()) {
                    foreach ($terms as $cate) {
                        $sitemap->add(url($_service . '/' . $cate->term_name), date(DATE_ISO8601, strtotime(date('Y-m-d H:i:s'))), '1.0', 'daily');
                    }
                }
            } else {
                $service_config = array_keys(admin_config('services', 'seo'));
                $service = 'post';
                $page = 1;
                if (strpos($_service, '-') !== false) {
                    $temp = explode('-', $_service);
                    if (count($temp) == 2) {
                        $service = $temp[0];
                        $page = $temp[1];
                    }
                } else {
                    $service = $_service;
                    $page = 1;
                }

                if (!in_array($service, $service_config)) {
                    $service = 'post';
                }

                if (!is_numeric($page) || $page < 1) {
                    $page = 1;
                }

                $enable = get_option($service . '_enable', 'on');

                if ($enable == 'on') {
                    $sitemap->setCache('laravel.sitemap.' . $service . '-' . $page, 1);

                    $posts_per_page = admin_config('posts_per_page', 'seo');
                    $offset = ($page - 1) * $posts_per_page;
                    $posts = DB::table('gmz_' . $service)->where('status', 'publish')->limit($posts_per_page)->offset($offset)->orderByDesc('id')->get();

                    if (!$posts->isEmpty()) {
                        foreach ($posts as $post) {
                            $func = 'get_' . $service . '_permalink';
                            $sitemap->add($func($post->post_slug), $post->updated_at, '1.0', 'daily');
                        }
                    }
                }
            }

            return $sitemap->render('xml');
        }
    }
}