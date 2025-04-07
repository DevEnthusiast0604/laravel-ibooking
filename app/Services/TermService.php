<?php

namespace App\Services;

use App\Repositories\BeautyRepository;
use App\Repositories\TaxonomyRepository;
use App\Repositories\TermRepository;
use Illuminate\Support\Str;

class TermService extends AbstractService
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
        $this->repository = TermRepository::inst();
    }

    public function getTermByID($id)
    {
        return $this->repository->find($id);
    }

    public function deleteTerm($request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);
        $term_id = isset($params['termID']) ? $params['termID'] : '';
        $term_hashing = isset($params['termHashing']) ? $params['termHashing'] : 'none';

        if (!gmz_compare_hashing($term_id, $term_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $this->repository->delete($term_id);

        return [
            'status' => 1,
            'message' => __('Delete term successfully'),
        ];
    }

    public function editTerm($request)
    {
        $post_data = $request->all();
        $tax_id = $post_data['taxonomy_id'];
        $tax_hashing = $post_data['taxonomy_hashing'];
        $tax_name = $post_data['taxonomy_name'];
        $tax_name_hashing = $post_data['taxonomy_name_hashing'];
        if (!gmz_compare_hashing($tax_id, $tax_hashing) || !gmz_compare_hashing($tax_name, $tax_name_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid.')
            ];
        }
        $post_data = $this->mergeData($post_data, $post_data['fields']);

        $term_id = $post_data['term_id'];

        $term_title = $post_data['term_title'];
        $term_title_for_slug = $term_title;
        $is_like = false;
        if (strpos($term_title, '[:]')) {
            $term_title_arr = explode('[:', $term_title);
            $term_title = '[:' . $term_title_arr[1] . '[:';
            $start = strpos($term_title, ']');
            $end = strpos($term_title, '[');
            $term_title_for_slug = substr($term_title, ($start + 1), ($end - $start + 2));
            $is_like = true;
        }

        $existing = $this->repository->checkExistsByTitle($term_title, $post_data['taxonomy_id'], $term_id, $is_like);

        if ($existing) {
            return [
                'status' => 0,
                'message' => __('This term already exists.')
            ];
        }

        $post_data['term_name'] = Str::slug($term_title_for_slug);

        unset($post_data['term_id']);

        $updated = $this->repository->update($term_id, $post_data);

        if ($updated) {

            if ($tax_name == 'beauty-branch' && isset($post_data['term_location'])) {
                //Location sync with service beauty
                $beautyRepo = BeautyRepository::inst();
                if ($beautyRepo->findOneBy(['branch' => $term_id])) {
                    $location_data = $post_data['term_location'];
                    $sync = $beautyRepo->updateByWhere(
                        ['branch' => $term_id],
                        [
                            'location_lat' => $location_data['lat'],
                            'location_lng' => $location_data['lng'],
                            'location_zoom' => $location_data['zoom'],
                            'location_address' => $post_data['term_description']
                        ]
                    );
                    if (!$sync) {
                        return [
                            'status' => 0,
                            'message' => __('Sync failed.')
                        ];
                    }
                }
            }
            return [
                'status' => 1,
                'message' => __('Update term successfully'),
            ];
        }

        return [
            'status' => 0,
            'message' => __('Update term failed')
        ];
    }

    public function newTerm($request)
    {
        $post_data = $request->all();
        $tax_id = $post_data['taxonomy_id'];
        $tax_hashing = $post_data['taxonomy_hashing'];
        $tax_name = $post_data['taxonomy_name'];
        $tax_name_hashing = $post_data['taxonomy_name_hashing'];
        if (!gmz_compare_hashing($tax_id, $tax_hashing) || !gmz_compare_hashing($tax_name, $tax_name_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid.')
            ];
        }
        $post_data = $this->mergeData($post_data, $post_data['fields']);
        $term_title = $post_data['term_title'];
        $term_title_for_slug = $term_title;
        $is_like = false;
        if (strpos($term_title, '[:]')) {
            $term_title_arr = explode('[:', $term_title);
            $term_title = '[:' . $term_title_arr[1] . '[:';
            $start = strpos($term_title, ']');
            $end = strpos($term_title, '[');
            $term_title_for_slug = substr($term_title, ($start + 1), ($end - $start + 2));
            $is_like = true;
        }
        $existing = $this->repository->checkExistsByTitle($term_title, $tax_id, '', $is_like);

        if ($existing) {
            return [
                'status' => 0,
                'message' => __('This term already exists.')
            ];
        }

        $post_data['term_name'] = Str::slug($term_title_for_slug);

        if (isset($post_data['term_location'])) {
            $post_data['term_location'] = json_encode($post_data['term_location']);
        }

        $post_data['author'] = get_current_user_id();

        $inserted = $this->repository->save($post_data);

        if ($inserted) {
            return [
                'status' => 1,
                'message' => __('Add new term successfully'),
                'redirect' => dashboard_url('term/' . $tax_name)
            ];
        }

        return [
            'status' => 0,
            'message' => __('Add new term failed')
        ];
    }

    private function mergeData($post_data, $current_options)
    {
        $current_options = json_decode(base64_decode($current_options), true);
        if (!empty($current_options)) {
            foreach ($current_options as $item) {
                if (isset($item['translation']) && $item['translation']) {
                    $post_data[$item['id']] = set_translate($item['id']);
                } else {
                    $post_data[$item['id']] = request()->get($item['id'], '');
                }
            }
        }
        return $post_data;
    }

    public function getPostsPagination($number = 10, $where = [])
    {
        return $this->repository->paginate($number, $where);
    }

    public function getTaxonomyByName($tax_name)
    {
        $taxRepo = TaxonomyRepository::inst();
        return $taxRepo->where(['taxonomy_name' => $tax_name], true);
    }
}