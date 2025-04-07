<?php

namespace App\Services;

use App\Repositories\SeoRepository;
use Illuminate\Http\Request;

class SeoService extends AbstractService
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
        $this->repository = SeoRepository::inst();
    }

    public function saveSettings(Request $request)
    {
        $postID = $request->post('post_id', '');
        $postIDHashing = $request->post('post_id_hashing', '');
        $postType = $request->post('post_type', 'post');
        $postTypeHashing = $request->post('post_type_hashing', '');
        if (gmz_compare_hashing($postID, $postIDHashing) && gmz_compare_hashing($postType, $postTypeHashing)) {
            $checkExists = $this->repository->where([
                'post_id' => $postID,
                'post_type' => $postType
            ], true);

            $data = [
                'seo_title' => set_translate('seo_title_' . $postType),
                'meta_description' => set_translate('meta_description_' . $postType),
                'seo_image_facebook' => $request->post('seo_image_facebook_' . $postType, ''),
                'seo_title_facebook' => set_translate('seo_title_facebook_' . $postType),
                'meta_description_facebook' => set_translate('meta_description_facebook_' . $postType),
                'seo_image_twitter' => $request->post('seo_image_twitter_' . $postType, ''),
                'seo_title_twitter' => set_translate('seo_title_twitter_' . $postType),
                'meta_description_twitter' => set_translate('meta_description_twitter_' . $postType),
            ];

            if (!$checkExists) {
                $data['post_id'] = $postID;
                $data['post_type'] = $postType;
                $res = $this->repository->create($data);
            } else {
                $res = $checkExists->update($data);
            }

            if ($res) {
                return [
                    'status' => 1,
                    'message' => __('Saving data successfully')
                ];
            }
        }

        return [
            'status' => 0,
            'message' => __('Saving data failed')
        ];
    }
}