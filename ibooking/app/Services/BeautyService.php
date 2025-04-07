<?php

namespace App\Services;


use App\Jobs\SendEnquiryJob;
use App\Repositories\AgentAvailabilityRepository;
use App\Repositories\AgentRelationRepository;
use App\Repositories\AgentRepository;
use App\Repositories\BeautyAvailabilityRepository;
use App\Repositories\BeautyRepository;
use App\Repositories\CommentRepository;
use App\Repositories\SeoRepository;
use App\Repositories\TermRelationRepository;
use App\Repositories\TermRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BeautyService extends AbstractService
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
        $this->repository = BeautyRepository::inst();
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

    public function getWishList($number, $wishlist)
    {
        return $this->repository->getWishlist($number, $wishlist);
    }

    public function sendEnquiry(Request $request)
    {
        $postID = $request->post('post_id');
        $postHashing = $request->get('post_hashing');

        if (!gmz_compare_hashing($postID, $postHashing)) {
            return [
                'status' => false,
                'message' => __('Data is invalid')
            ];
        }

        $valid = Validator::make($request->all(), [
            'full_name' => ['required'],
            'email' => ['required', 'email'],
            'content' => ['required']
        ]);

        if ($valid->fails()) {
            return [
                'status' => 0,
                'message' => $valid->errors()->first()
            ];
        }

        $post_object = get_post($postID, GMZ_SERVICE_BEAUTY);
        $postType = get_post_type_by_object($post_object);
        $request->request->add(['post_type' => $postType]);
        if (!empty($post_object)) {

            dispatch(new SendEnquiryJob($request->all(), $post_object));

            return [
                'status' => true,
                'message' => __('Send your request successfully. Please wait response from owner of this service.')
            ];
        }
        return [
            'status' => false,
            'message' => __('Data is invalid')
        ];
    }

    public function deletePostTemp()
    {
        return $this->repository->hardDeleteByWhere([
            'author' => get_current_user_id(),
            'status' => 'temp'
        ]);
    }

    public function storeNewPost()
    {
        $data = [
            'post_title' => 'New beauty service ' . time(),
            'post_slug' => Str::slug('New beauty service ' . time()),
            'author' => get_current_user_id(),
            'status' => 'temp'
        ];
        do_action('gmz_store_new_post', 'beauty', $data);
        return $this->repository->save($data);
    }

    public function getPostById($id)
    {
        return $this->repository->find($id);
    }

    public function getPostBySlug($slug)
    {
        $data = $this->repository->where(['post_slug' => $slug], true);
        if ($data) {
            if (is_seo_enable()) {
                $seoRepo = SeoRepository::inst();
                $seo = $seoRepo->where([
                    'post_id' => $data->id,
                    'post_type' => GMZ_SERVICE_BEAUTY
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

    public function savePost(Request $request)
    {
        $post_id = $request->post('post_id', '');

        if (!empty($post_id)) {

            $current_options = $request->post('current_options', '');
            $current_options = json_decode(base64_decode($current_options), true);

            $post_data = $this->mergeData($request->all(), $current_options);

            if (isset($post_data['post_title'])) {
                $post_data['post_slug'] = $this->createSlug($post_data);
            }

            if (isset($post_data['post_content'])) {
                $post_data['post_content'] = balance_tags($post_data['post_content']);
            }

            if (isset($post_data['base_price'])) {
                $post_data['base_price'] = floatval($post_data['base_price']);
            }

            if (isset($post_data['min_day_booking'])) {
                $post_data['min_day_booking'] = intval($post_data['min_day_booking']);
            }

            if (isset($post_data['min_day_stay'])) {
                $post_data['min_day_stay'] = intval($post_data['min_day_stay']);
            }

            if (isset($post_data['cancel_before'])) {
                $post_data['cancel_before'] = intval($post_data['cancel_before']);
            }

            if (!empty($post_data['branch'])) {
                $termRepo = TermRepository::inst();
                $branch = $termRepo->find($post_data['branch']);
                if (!empty($branch)) {
                    $branch_location = json_decode($branch['term_location'], true);
                    $post_data['location_zoom'] = $branch_location['zoom'];
                    $post_data['location_lat'] = $branch_location['lat'];
                    $post_data['location_lng'] = $branch_location['lng'];
                    $post_data['location_address'] = $branch['term_description'];
                }
            }
            if (isset($post_data['agent'])) {
                $this->updateAgent($post_id, $post_data['agent']);
            }
            if (isset($post_data['service_starts'])) {
                $post_data['service_starts'] = hours_to_seconds($post_data['service_starts']);
            }
            if (isset($post_data['service_ends'])) {
                $post_data['service_ends'] = hours_to_seconds($post_data['service_ends']);
            }
            if (isset($post_data['service_duration'])) {
                $post_data['service_duration'] = hours_to_seconds($post_data['service_duration']);
            }
            if (!empty($post_data['day_off_week'])) {
                $post_data['day_off_week'] = implode(',', $post_data['day_off_week']);
            }

            $post_data = $this->updateTerm($post_id, $post_data);


            //Status
            if (isset($post_data['status'])) {
                $current_post = get_post($post_id, GMZ_SERVICE_BEAUTY);
                $current_status = $current_post['status'];
                $need_approve = get_option('beauty_approve', 'off');
                if ($need_approve == 'on') {
                    if (is_partner()) {
                        if ($current_status == 'temp' || $current_status == 'pending') {
                            $post_data['status'] = 'pending';
                        } else {
                            if (!in_array($post_data['status'], ['publish', 'draft'])) {
                                $post_data['status'] = 'draft';
                            }
                        }
                    }
                } else {
                    if (is_partner()) {
                        if (!in_array($post_data['status'], ['publish', 'draft'])) {
                            $post_data['status'] = 'draft';
                        }
                    }
                }
            }
            //End status
            $updated = $this->repository->update($post_id, $post_data);

            if ($updated) {
                $response = [
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Saving data successfully'),
                ];

                if ($request->post('post_slug')) {
                    $response['permalink'] = get_beauty_permalink($updated['post_slug']);
                }

                $finish = $request->post('finish', '');
                if ($finish) {
                    $response['redirect'] = dashboard_url('edit-beauty/' . $post_id);
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
            $isNewSlug = strpos($data['post_slug'], 'new-beauty-');
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

    private function updateAgent($post_id, $agent_data)
    {
        $agentRelationRepo = AgentRelationRepository::inst();
        $post_type = GMZ_SERVICE_BEAUTY;
        $agentRelationRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => $post_type
        ]);

        if (!empty($agent_data)) {
            $insert_data = array();
            foreach ($agent_data as $agent_id) {
                $data['agent_id'] = $agent_id;
                $data['post_id'] = $post_id;
                $data['post_type'] = $post_type;
                $insert_data[] = $data;
            }
            $agentRelationRepo->insert($insert_data);
        }
    }

    private function mergeData($post_data, $current_options)
    {
        if (!empty($current_options)) {
            $exclude_translate = ['checkbox', 'select', 'list_item'];

            foreach ($current_options as $item) {
                if (isset($item['translation']) && $item['translation'] && !in_array($item['type'], $exclude_translate)) {
                    $post_data[$item['id']] = set_translate($item['id']);
                } else {
                    if ($item['type'] == 'location') {
                        $location = $post_data[$item['type']];
                        if (isset($location['address']) && is_array($location['address'])) {
                            $location_temp = '';
                            foreach ($location['address'] as $akey => $aval) {
                                $location_temp .= '[:' . $akey . ']' . $aval;
                            }
                            $location_temp .= '[:]';
                            $location['address'] = $location_temp;
                        }
                        if (!empty($location)) {
                            foreach ($location as $lc_key => $lc_val) {
                                $post_data[$item['type'] . '_' . $lc_key] = $lc_val;
                            }
                        }
                    }
                    if ($item['type'] == 'list_item') {
                        if (isset($post_data[$item['id']])) {
                            $value = $post_data[$item['id']];
                            $langs = get_languages();
                            $return = [];
                            if (count($langs) > 0) {

                                $field_need_trans = [];
                                foreach ($item['fields'] as $fkey => $fval) {
                                    if (isset($fval['translation']) && $fval['translation']) {
                                        array_push($field_need_trans, $fval['id']);
                                    }
                                }

                                if (!empty($value)) {
                                    foreach ($value as $key => $val) {
                                        if (!empty($val)) {
                                            foreach ($val as $key1 => $val1) {
                                                if (in_array($key, $field_need_trans)) {
                                                    $str = '';
                                                    foreach ($val1 as $key2 => $val2) {
                                                        $str .= '[:' . $langs[$key2] . ']' . $val2;
                                                    }
                                                    $str .= '[:]';
                                                    $return[$key][$key1][0] = $str;
                                                } else {
                                                    $return[$key][$key1] = $val1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if (empty($return)) {
                                $return = $value;
                            }

                            $list_item_data = [];
                            if (!empty($return)) {
                                foreach ($return as $key => $val) {
                                    foreach ($val as $child_key => $child_val) {
                                        $list_item_data[$child_key][$key] = $child_val[0];
                                    }
                                }
                                $post_data[$item['id']] = serialize($list_item_data);
                            } else {
                                $post_data[$item['id']] = [];
                            }
                        } else {
                            $post_data[$item['id']] = [];
                        }
                    }
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
        if (isset($post_data['service'])) {
            $all_types = get_terms('name', 'beauty-services', 'id');

            if (!empty($all_types)) {
                $type_in_str = '(' . implode(',', $all_types) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'beauty' AND term_id IN {$type_in_str}");
            }

            $service = $post_data['service'];
            if (!empty($service)) {
                $data_insert = [
                    'term_id' => $service,
                    'post_id' => $post_id,
                    'post_type' => GMZ_SERVICE_BEAUTY
                ];
                $termRelationRepo->create($data_insert);
                $post_data['service'] = $service;
            } else {
                $post_data['service'] = '';
            }
        }

        if (isset($post_data['branch'])) {
            $all_types = get_terms('name', 'beauty-branch', 'id');

            if (!empty($all_types)) {
                $type_in_str = '(' . implode(',', $all_types) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'beauty' AND term_id IN {$type_in_str}");
            }

            $data = $post_data['branch'];
            if (!empty($data)) {
                $data_insert = [
                    'term_id' => $data,
                    'post_id' => $post_id,
                    'post_type' => GMZ_SERVICE_BEAUTY
                ];
                $termRelationRepo->create($data_insert);
                $post_data['branch'] = $data;
            } else {
                $post_data['branch'] = '';
            }
        }

        return $post_data;
    }

    public function getPostsPagination($number = 10, $where = [])
    {
        if (is_partner()) {
            $where['author'] = get_current_user_id();
        }
        return $this->repository->paginate($number, $where, true);
    }

    public function restoreBeauty($request)
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
            'post_type' => GMZ_SERVICE_BEAUTY
        ]);
        $commentRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_BEAUTY
        ]);
        $seoRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_BEAUTY
        ]);

        restore_wishlist($post_id, GMZ_SERVICE_BEAUTY);

        return [
            'status' => 1,
            'message' => __('Restore successfully'),
        ];
    }

    public function hardDeleteBeauty($request)
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
        $beautyAvaiRepo = BeautyAvailabilityRepository::inst();
        $agentRelationRepo = AgentRelationRepository::inst();
        $seoRepo = SeoRepository::inst();

        $termRelationRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_BEAUTY
        ]);

        $commentRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_BEAUTY
        ]);

        $seoRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_BEAUTY
        ]);

        $beautyAvaiRepo->deleteByWhere([
            'post_id' => $post_id
        ]);

        $agentRelationRepo->deleteByWhere([
            'post_id' => $post_id
        ]);

        hard_delete_wishlist($post_id, GMZ_SERVICE_BEAUTY);

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
            'post_type' => GMZ_SERVICE_BEAUTY
        ]);

        $commentRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_BEAUTY
        ]);

        $seoRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_BEAUTY
        ]);

        $this->repository->delete($post_id);

        delete_wishlist($post_id, GMZ_SERVICE_BEAUTY);

        return [
            'status' => 1,
            'message' => __('Delete successfully'),
            'reload' => true
        ];
    }

    public function getSearchResult(Request $request)
    {
        $default = [
            'page' => 1,
            'lat' => '0',
            'lng' => '0',
            'address' => '',
            'checkIn' => '',
            'service' => '',
            'limit' => intval(get_option('beauty_search_number', 6)),
            'layout' => 'list',
            'sort' => 'new'
        ];

        $allParams = $request->all();

        $checkIn = $request->post('checkIn', '');
        $checkIn = strtotime($checkIn);

        if (is_numeric($checkIn) && $checkIn >= strtotime("today")) {
            $checkIn = date('Y-m-d', $checkIn);
            $checkIn = strtotime("midnight " . $checkIn);
        }

        $allParams['checkIn'] = $checkIn;

        $service = $request->post('service', '');
        if (!is_numeric($service) || $service < 1) {
            $service = '';
        }
        $allParams['service'] = $service;


        $params = gmz_parse_args($allParams, $default);

        $data = $this->repository->getSearchResult($params);
        $data_collection = $data;
        $data = $data->toArray();
        if ($params['page'] == 1) {
            $data['search_string'] = get_search_string('beauty', $data['total'], $params);
        } else {
            $data['search_string'] = '';
        }

        if ($data['current_page'] < $data['last_page']) {
            $data['pagination'] = '<button class="btn btn-info text-center" data-page="' . ($data['current_page'] + 1) . '">' . __('Load More') . '</button>';
        } else {
            $data['pagination'] = '<button class="btn btn-info text-center disabled" data-page="' . $data['to'] . '">' . __('No more') . '</button>';
        }

        if ($params['layout'] == 'list') {
            $html = view('Frontend::services.beauty.search.result-data', ['data' => $data['data']])->render();
        } else {
            $html = '<div class="row">';
        }

        $map = [];
        if ($data_collection->total() > 0) {
            foreach ($data_collection->items() as $k => $v) {
                $map[] = [
                    'id' => $v['id'],
                    'lat' => $v['location_lat'],
                    'lng' => $v['location_lng'],
                    'price' => convert_price($v['base_price'])
                ];

                if ($params['layout'] != 'list') {
                    $html .= '<div class="col-lg-6 col-md-6 col-sm-12">';
                    $html .= view('Frontend::services.beauty.items.grid-item', ['item' => $v])->render();
                    $html .= '</div>';
                }
            }
        }

        if ($params['layout'] != 'list') {
            $html .= '</div>';
        }

        $data['html'] = $html;
        $data['map'] = $map;

        return $data;
    }

    public function getSlotEmptyByDay(int $id, $unixtime)
    {
        if ($unixtime) {
            $unixtime = date('Y-m-d', $unixtime);
            $unixtime = strtotime("midnight " . $unixtime);
        } else {
            return false;
        }

        // check availability service
        $srv = $this->repository->checkAvailability($unixtime, $id);

        if (empty($srv)) {
            return false;
        } else {
            $srv = $srv[0];
        }

        //get slot list
        $slot = $this->getSlotList($srv, $unixtime);

        $agentRepo = AgentRepository::inst();
        //get agent available of service
        $availableAgents = explode(',', $srv['agent_ids']);
        $agentOfService = $agentRepo->whereIn('id', $availableAgents);
        $agentOfService = $agentOfService->toArray();

        return( [
            'slot' => $slot,
            'agent' => $agentOfService
        ]);
    }

    protected function getSlotList($serviceData, $unixtime)
    {
        $agentAvaiRepo = AgentAvailabilityRepository::inst();
        //available agents
        $availableAgents = explode(',', $serviceData['agent_ids']);
        $slotsBooked = $agentAvaiRepo->getSlotBooked($availableAgents);
        $srv_start = $serviceData['service_starts'];
        $srv_end = $serviceData['service_ends'];
        $srv_duration = $serviceData['service_duration'];
        $slot = array();

        for ($i = $srv_start; $i < $srv_end; $i += 900) {
            $start = $i + $unixtime;
            $end = $i + $srv_duration + $unixtime;
            $agentInSlot = $availableAgents;
            foreach ($slotsBooked as $booked) {
                $key_agent = array_search($booked['post_id'], $agentInSlot);
                if (!empty($key_agent)) {
                    if (($start >= $booked['check_in'] && $start < $booked['check_out']) || ($end > $booked['check_in'] && $end <= $booked['check_out'])) {
                        unset($agentInSlot[$key_agent]);
                    }
                }
            }
            if (!empty($agentInSlot)) {
                $slot[] = [
                    'start' => $start,
                    'end' => $end,
                    'agent' => $agentInSlot,
                    'space' => count($agentInSlot)
                ];
            }
        }

        return $slot;
    }

    public function getFullPostById($id)
    {
        $post = $this->repository->find($id);
        if (is_seo_enable()) {
            $seoRepo = SeoRepository::inst();
            $seo = $seoRepo->where([
                'post_id' => $id,
                'post_type' => GMZ_SERVICE_BEAUTY
            ], true);
            if ($seo) {
                $post['seo'] = $seo->toArray();
            } else {
                $post['seo'] = [];
            }
        }
        $post->agent;
        return $post->toArray();
    }

    public function getCustomPriceByDay(int $id, $unixtime)
    {
        if ($unixtime) {
            $unixtime = date('Y-m-d', $unixtime);
            $unixtime = strtotime("midnight " . $unixtime);
        } else {
            return false;
        }

        $beautyAvaiRepo = BeautyAvailabilityRepository::inst();

        $result = $beautyAvaiRepo->getCustomPriceByDay($id, $unixtime);
        return isset($result['price']) ? $result['price'] : '';
    }

    public function addToCart(Request $request)
    {
        $post_id = $request->post('post_id');
        $post_hashing = $request->post('post_hashing');
        $check_in = $request->post('check_in');
        $agent = $request->post('agent');

        if (gmz_compare_hashing($post_id, $post_hashing) && $check_in) {
            $beauty_object = $this->repository->find($post_id);

            if (empty($beauty_object) || !is_numeric($check_in)) {
                return [
                    'status' => false,
                    'message' => __('Something went wrong, please reload the web page.')
                ];
            }

            $slot_data = $this->getSlotEmptyByDay($post_id, $check_in);
            $custom_price = $this->getCustomPriceByDay($post_id, $check_in);
            $check_out = false;
            $agent_data = '';

            if (isset($slot_data['slot'])) {
                foreach ($slot_data['slot'] as $value) {
                    if ($value['start'] == $check_in && in_array($agent, $value['agent'], true)) {
                        $check_out = $value['end'];
                        break;
                    }
                }
            }

            if (isset($slot_data['agent'])) {
                foreach ($slot_data['agent'] as $value) {
                    if ($value['id'] == $agent) {
                        $agent_data = $value;
                        break;
                    }
                }
            }

            if ($check_out) {

                $data = [
                    'post_id' => $post_id,
                    'check_in' => $check_in,
                    'check_out' => $check_out,
                    'agent_id' => $agent,
                    'agent_data' => $agent_data,
                    'coupon_data' => []
                ];

                $base_price = empty($custom_price) ? $beauty_object['base_price'] : $custom_price;
                $total = $base_price;
                $tax = get_tax();
                if ($tax['included'] == 'off') {
                    $total += ($total * $tax['percent'] / 100);
                }

                $cart_data = [
                    'post_id' => $post_id,
                    'post_object' => serialize($beauty_object),
                    'post_type' => 'beauty',
                    'base_price' => $base_price,
                    'tax' => $tax,
                    'coupon' => '',
                    'coupon_percent' => 0,
                    'coupon_value' => 0,
                    'sub_total' => $base_price,
                    'total' => $total,
                    'adult' => 1,
                    'cart_data' => $data,
                ];

                \Cart::inst()->setCart($cart_data);

                return [
                    'status' => true,
                    'redirect' => url('checkout'),
                ];
            } else {
                return [
                    'status' => false,
                    'message' => __('Something went wrong, please reload the web page.')
                ];
            }
        }
        return [
            'status' => false,
            'message' => __('Data is invalid')
        ];
    }
}