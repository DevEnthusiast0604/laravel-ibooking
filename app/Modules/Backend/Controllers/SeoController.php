<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Services\SeoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = SeoService::inst();
    }

    public function saveSingleSettingsAction(Request $request)
    {
        $response = $this->service->saveSettings($request);
        return response()->json($response);
    }

    public function saveSettingsAction(Request $request)
    {
        $seo_enable = $request->post('seo_enable', 'off');

        $seo_separator = $request->post('seo_separator', 'dash');
        $seo_robot = $request->post('seo_robot', '');
        $seo_enable_sitemap = $request->post('seo_enable_sitemap', 'off');
        $google_code = $request->post('google_code', '');
        $bing_code = $request->post('bing_code', '');
        $yandex_code = $request->post('yandex_code', '');
        $baidu_code = $request->post('baidu_code', '');
        update_opt('seo_enable', $seo_enable);
        update_opt('seo_separator', $seo_separator);
        update_opt('seo_robots', json_encode($seo_robot));
        update_opt('seo_enable_sitemap', $seo_enable_sitemap);
        update_opt('seo_google_code', $google_code);
        update_opt('seo_bing_code', $bing_code);
        update_opt('seo_yandex_code', $yandex_code);
        update_opt('seo_baidu_code', $baidu_code);

        $seo_page_fields = admin_config('page', 'seo');
        $seo_content_fields = admin_config('content', 'seo');
        $seo_fields = [
            'page' => $seo_page_fields['items'],
            'content' => $seo_content_fields['items']
        ];
        foreach ($seo_fields as $key => $val) {
            foreach ($val as $page) {
                $page_id = $page['id'];
                $option_key = 'seo_' . $key . '_' . $page_id;
                $option_value = [
                    'seo_enable' => $request->post('seo_enable_' . $page_id, 'off'),
                    'seo_title' => set_translate('seo_title_' . $page_id),
                    'meta_description' => set_translate('meta_description_' . $page_id),
                    'seo_image_facebook' => $request->post('seo_image_facebook_' . $page_id, ''),
                    'seo_title_facebook' => set_translate('seo_title_facebook_' . $page_id),
                    'meta_description_facebook' => set_translate('meta_description_facebook_' . $page_id),
                    'seo_image_twitter' => $request->post('seo_image_twitter_' . $page_id, ''),
                    'seo_title_twitter' => set_translate('seo_title_twitter_' . $page_id),
                    'meta_description_twitter' => set_translate('meta_description_twitter_' . $page_id),
                ];
                update_opt($option_key, json_encode($option_value));
            }
        }

        Cache::forget('gmz_seo');

        return response()->json([
            'status' => 1,
            'message' => __('Update successfully'),
        ]);
    }

    public function seoView()
    {
        return $this->getView($this->getFolderView('seo.index'));
    }
}