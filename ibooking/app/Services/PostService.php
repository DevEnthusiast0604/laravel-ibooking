<?php

namespace App\Services;

use App\Repositories\CommentRepository;
use App\Repositories\PostRepository;
use App\Repositories\SeoRepository;
use App\Repositories\TermRelationRepository;
use App\Repositories\TermRepository;
use Illuminate\Support\Str;

class PostService extends AbstractService
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
        $this->repository = PostRepository::inst();
    }

    public function changeStatus($request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);
        $post_id = isset($params['postID']) ? $params['postID'] : '';
        $post_hashing = isset($params['postHashing']) ? $params['postHashing'] : 'none';

        if (!gmz_compare_hashing($post_id, $post_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }
        $post = $this->repository->find($post_id);
        $statusTo = isset($params['statusTo']) ? $params['statusTo'] : '';
        $res = false;
        if ($post->status != $statusTo) {
            $res = $this->repository->update($post_id, [
                'status' => $statusTo
            ]);
        }
        if ($res) {
            return [
                'status' => 1,
                'message' => __('Update successfully'),
                'reload' => 1
            ];
        }

        return [
            'status' => 0,
            'message' => __('Data is invalid')
        ];
    }

    public function getArchivePagination($number, $where)
    {
        return $this->repository->getArchivePagination($number, $where);
    }

    public function getPostBySlug($slug)
    {
        $data = $this->repository->where(['post_slug' => $slug], true);
        if ($data) {
            if (is_seo_enable()) {
                $seoRepo = SeoRepository::inst();
                $seo = $seoRepo->where([
                    'post_id' => $data->id,
                    'post_type' => 'post'
                ], true);
                if ($seo) {
                    $data['seo'] = $seo->toArray();
                } else {
                    $data['seo'] = [];
                }
            }
        }
        return $data;
    }

    public function restorePost($request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);
        $post_id = isset($params['postID']) ? $params['postID'] : '';
        $post_hashing = isset($params['postHashing']) ? $params['postHashing'] : 'none';

        if (!gmz_compare_hashing($post_id, $post_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $this->repository->restore($post_id);

        $termRelationRepo = TermRelationRepository::inst();
        $commentRepo = CommentRepository::inst();
        $seoRepo = SeoRepository::inst();

        $termRelationRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => 'post'
        ]);
        $commentRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => 'post'
        ]);
        $seoRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => 'post'
        ]);

        return [
            'status' => 1,
            'message' => __('Restore successfully'),
        ];
    }

    public function hardDeletePost($request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);
        $post_id = isset($params['postID']) ? $params['postID'] : '';
        $post_hashing = isset($params['postHashing']) ? $params['postHashing'] : 'none';

        if (!gmz_compare_hashing($post_id, $post_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $termRelationRepo = TermRelationRepository::inst();
        $commentRepo = CommentRepository::inst();
        $seoRepo = SeoRepository::inst();

        $termRelationRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => 'post'
        ]);

        $commentRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => 'post'
        ]);

        $seoRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => 'post'
        ]);

        $this->repository->hardDelete($post_id);

        return [
            'status' => 1,
            'message' => __('Delete successfully'),
        ];
    }

    public function deletePost($request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);
        $post_id = isset($params['postID']) ? $params['postID'] : '';
        $post_hashing = isset($params['postHashing']) ? $params['postHashing'] : 'none';

        if (!gmz_compare_hashing($post_id, $post_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $termRelationRepo = TermRelationRepository::inst();
        $commentRepo = CommentRepository::inst();
        $seoRepo = SeoRepository::inst();

        $termRelationRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => 'post'
        ]);

        $commentRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => 'post'
        ]);

        $seoRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => 'post'
        ]);

        $this->repository->delete($post_id);

        return [
            'status' => 1,
            'message' => __('Delete successfully'),
        ];
    }

    public function getPostsPagination($number = 10, $where = [])
    {
        return $this->repository->paginate($number, $where, true);
    }

    public function deletePostTemp()
    {
        return $this->repository->deleteByWhere([
            'author' => get_current_user_id(),
            'status' => 'temp'
        ]);
    }

    public function storeNewPost()
    {
        return $this->repository->save([
            'post_title' => 'New post ' . time(),
            'post_slug' => Str::slug('New post ' . time()),
            'author' => get_current_user_id(),
            'status' => 'temp'
        ]);
    }

    public function getPostById($id)
    {
        return $this->repository->find($id);
    }

    public function createSlug($data)
    {
        $text_slug = $data['post_title'];
        if (strpos($text_slug, '[:]')) {
            $text_slug_arr = explode('[:', $text_slug);
            $text_slug = '[:' . $text_slug_arr[1] . '[:';
            $start = strpos($text_slug, ']');
            $end = strpos($text_slug, '[');
            $text_slug = substr($text_slug, ($start + 1), ($end - $start + 2));
        }

        if (!empty($data['post_slug'])) {
            $isNewSlug = strpos($data['post_slug'], 'new-post-');
            if ($isNewSlug === false) {
                $text_slug = $data['post_slug'];
            }
        }

        $slug = Str::slug($text_slug);

        $allSlugs = $this->repository->getRelatedSlugs($slug, $data['post_id']);

        if (!$allSlugs->contains('post_slug', $slug)) {
            return $slug;
        }

        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('post_slug', $newSlug)) {
                return $newSlug;
            }
        }

        return $slug . '-' . time();
    }

    private function mergeData($post_data, $current_options)
    {
        if (!empty($current_options)) {
            $exclude_translate = ['checkbox'];
            foreach ($current_options as $item) {
                if (isset($item['translation']) && $item['translation'] && !in_array($item['type'], $exclude_translate)) {
                    $post_data[$item['id']] = set_translate($item['id']);
                } else {
                    $post_data[$item['id']] = request()->get($item['id'], '');
                }
                if (!isset($post_data[$item['id']])) {
                    $post_data[$item['id']] = '';
                }
            }
        }
        return $post_data;
    }

    private function updateTerm($post_id, $post_data)
    {
        $termRelationRepo = TermRelationRepository::inst();
        if (isset($post_data['post_category'])) {
            $all_categories = get_terms('name', 'post-category', 'id');

            if (!empty($all_categories)) {
                $cate_in_str = '(' . implode(',', $all_categories) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'post' AND term_id IN {$cate_in_str}");
            }

            $post_categories = $post_data['post_category'];
            if (!empty($post_categories) && is_array($post_categories)) {
                foreach ($post_categories as $item) {
                    $data_insert = [
                        'term_id' => $item,
                        'post_id' => $post_id,
                        'post_type' => 'post'
                    ];
                    $termRelationRepo->create($data_insert);
                }
                $post_data['post_category'] = implode(',', $post_categories);
            } else {
                $post_data['post_category'] = '';
            }
        }
        if (isset($post_data['post_tag'])) {
            $all_tags = get_terms('name', 'post-tag', 'id');
            $taxonomy = get_taxonomies('name', 'post-tag', 'id');
            $tax_id = 0;
            if (!empty($taxonomy)) {
                $tax_id = $taxonomy[0];
            }
            $tag_in_str = '';
            if (!empty($all_tags)) {
                $tag_in_str = '(' . implode(',', $all_tags) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'post' AND term_id IN {$tag_in_str}");
            }

            $post_tags = $post_data['post_tag'];

            $terms_id = [];

            if (!empty($post_tags)) {
                $termRepo = TermRepository::inst();
                $post_tags = explode(',', $post_tags);
                foreach ($post_tags as $tag) {
                    $tag = trim($tag);
                    if (!empty($all_tags)) {
                        $check_exists = $termRepo->whereRaw("term_title = '{$tag}' AND id IN {$tag_in_str}", true);
                        if ($check_exists) {
                            array_push($terms_id, $check_exists->id);
                        } else {
                            $new_tag = $termRepo->create([
                                'term_title' => $tag,
                                'term_name' => Str::slug($tag),
                                'taxonomy_id' => $tax_id
                            ]);
                            array_push($terms_id, $new_tag);
                        }
                    } else {
                        $new_tag = $termRepo->create([
                            'term_title' => $tag,
                            'term_name' => Str::slug($tag),
                            'taxonomy_id' => $tax_id
                        ]);
                        array_push($terms_id, $new_tag);
                    }
                }
            }

            if (!empty($terms_id)) {
                foreach ($terms_id as $item) {
                    $data_insert = [
                        'term_id' => $item,
                        'post_id' => $post_id,
                        'post_type' => 'post'
                    ];
                    $termRelationRepo->create($data_insert);
                }
                $post_data['post_tag'] = implode(',', $terms_id);
            } else {
                $post_data['post_tag'] = implode(',', $terms_id);
            }
        }
        return $post_data;
    }

    public function savePost($request)
    {
        $post_id = $request->post('post_id', '');
        if (!empty($post_id)) {
            $current_options = $request->post('current_options', '');
            $current_options = json_decode(base64_decode($current_options), true);

            $post_data = $this->mergeData($request->all(), $current_options);

            if (isset($post_data['post_title'])) {
                $post_data['post_slug'] = $this->createSlug($post_data);
            }

            $post_data = $this->updateTerm($post_id, $post_data);

            if (isset($post_data['post_content'])) {
                $post_data['post_content'] = balance_tags($post_data['post_content']);
            }

            $updated = $this->repository->update($post_id, $post_data);

            if ($updated) {
                $response = [
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Saving data successfully')
                ];

                if ($request->post('post_slug')) {
                    $response['permalink'] = get_post_permalink($updated['post_slug']);
                }

                $finish = $request->post('finish', '');


                if ($finish) {
                    $response['redirect'] = dashboard_url('edit-post/' . $post_id);
                }

                return $response;
            }
        }

        return [
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Saving data failed')
        ];
    }

    public function storeTermData($post_id)
    {
        $postData = $this->repository->find($post_id);
        if (!empty($postData)) {
            $termRelationRepo = TermRelationRepository::inst();
            $termRepo = TermRepository::inst();
            $tax = ['post_category', 'post_tag'];
            foreach ($tax as $item) {
                $taxName = str_replace('_', '-', $item);
                $allTax = get_terms('name', $taxName);

                $taxData = $termRelationRepo->where([
                    'post_id' => $postData->id,
                    'post_type' => 'post'
                ]);

                $res = [];
                if (!$taxData->isEmpty()) {
                    foreach ($taxData as $_item) {
                        if (isset($allTax[$_item->term_id])) {
                            array_push($res, $_item->term_id);
                        }
                    }
                }

                if ($item == 'post_category') {
                    $postData[$item] = implode(', ', $res);
                } else {
                    $postData[$item] = '';
                    if (!empty($res)) {
                        $res_in = '(' . implode(',', $res) . ')';
                        $tagData = $termRepo->whereRaw("id IN {$res_in}");
                        $tagStore = [];
                        if (!$tagData->isEmpty()) {
                            foreach ($tagData as $tag) {
                                array_push($tagStore, $tag->term_title);
                            }
                            $postData[$item] = implode(', ', $tagStore);
                        }
                    }
                }
            }
            if (is_seo_enable()) {
                $seoRepo = SeoRepository::inst();
                $seo = $seoRepo->where([
                    'post_id' => $post_id,
                    'post_type' => 'post'
                ], true);
                if ($seo) {
                    $postData['seo'] = $seo->toArray();
                } else {
                    $postData['seo'] = [];
                }
            }
            return $postData;
        }
        return false;
    }
}