<?php

namespace App\Services;

use App\Jobs\SendEnquiryJob;
use App\Repositories\CarAvailabilityRepository;
use App\Repositories\CarRepository;
use App\Repositories\CommentRepository;
use App\Repositories\OrderRepository;
use App\Repositories\SeoRepository;
use App\Repositories\TermRelationRepository;
use App\Repositories\TermRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CarService extends AbstractService
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
        $this->repository = CarRepository::inst();
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

        $post_object = get_post($postID, GMZ_SERVICE_CAR);
        $postType = get_post_type_by_object($post_object);
        $request->request->add(['post_type' => $postType]);
        if (!empty($post_object)) {

            dispatch(new SendEnquiryJob($request->all(), $post_object));
            //\GMZ_Mail::inst()->sendEmailCarEnquiry($post_object, $request->all());

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

    private function getInsurancePrice($insurances, $post_data, $number_day)
    {
        $total = 0;
        $data = [];
        if (!empty($post_data)) {
            $insurances = maybe_unserialize($insurances);
            if (!empty($insurances)) {
                foreach ($insurances as $key => $val) {
                    if (in_array($key, $post_data)) {
                        if ($val['fixed'] == 'on') {
                            $total += intval($val['price']);
                        } else {
                            $total += intval($val['price']) * $number_day;
                        }
                        $data[$key] = $val;
                    }
                }
            }
        }

        return [
            'price' => $total,
            'data' => $data
        ];
    }

    private function getEquipmentPrice($equipments, $post_data)
    {
        $total = 0;
        $data = [];
        if (!empty($post_data)) {
            $equipments = maybe_unserialize($equipments);
            if (!empty($equipments)) {
                foreach ($equipments as $key => $val) {
                    if ($val['choose'] == 'yes' && in_array($key, $post_data)) {
                        $term = get_term('id', $key);
                        if (!empty($val['price'])) {
                            $total += $val['price'];
                            $term->custom_price = $val['price'];
                        } else {
                            $term->custom_price = $term->term_price;
                            if ($term) {
                                $total += $term->term_price;
                            }
                        }
                        $data[] = $term;
                    }
                }
            }
        }

        return [
            'price' => $total,
            'data' => $data
        ];
    }

    public function getRealPrice($post_id, $check_in, $check_out, $number, $equipments, $insurances)
    {
        $check_in = strtotime($check_in);
        $check_out = strtotime($check_out);

        if ($number > 0) {
            $post_object = $this->repository->find($post_id);
            $price = (float)$post_object->base_price;
            $quantity = (int)$post_object->quantity;

            $number_day = gmz_date_diff($check_in, $check_out) + 1;

            $discount_by_days = $post_object['discount_by_day'];
            $has_discount_by_day = false;
            if (!empty($discount_by_days)) {
                $discount_by_days = maybe_unserialize($discount_by_days);
                if (!empty($discount_by_days) && $discount_by_days != '[]') {
                    foreach ($discount_by_days as $item) {
                        if ($number_day >= $item['from'] && $number_day <= $item['to']) {
                            $has_discount_by_day = true;
                            $price = $item['price'];
                            break;
                        }
                    }
                }
            }

            $total = 0;
            $total_avail = 0;
            $check_available = true;
            $carAvaiRepo = CarAvailabilityRepository::inst();

            $avails = $carAvaiRepo->getDataAvailability($post_id, $check_in, $check_out);

            if (!$avails->isEmpty()) {
                foreach ($avails as $avail) {
                    if ($avail['status'] == 'unavailable') {
                        $check_available = false;
                        break;
                    } else {
                        $total_avail += $avail['price'];
                    }
                }
            }

            $orderRepo = OrderRepository::inst();
            $orders = $orderRepo->getOrderItems($post_id, $check_in, $check_out, 'car');

            if (!empty($quantity) && $quantity > 0) {
                if (!$orders->isEmpty()) {
                    for ($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)) {
                        $booked = 0;
                        foreach ($orders as $item) {
                            if ($i >= $item['start_date'] && $i <= $item['end_date']) {
                                $booked += (int)$item['number'];
                            }
                        }
                        if (($quantity - $booked) < $number) {
                            $check_available = false;
                            break;
                        }
                    }
                }
            }

            if (!$check_available) {
                return [
                    'status' => false,
                    'message' => __('Date range is not available')
                ];
            } else {
                if (!$has_discount_by_day) {
                    $count_avail = $avails->count();
                    if ($number_day > $count_avail) {
                        $total += ($number_day - $count_avail) * $price;
                    }

                    $total += $total_avail;
                } else {
                    $total = $price * $number_day;
                }
            }

            $total = $total * $number;
            $base_price = $total;
            $price_equip = $this->getEquipmentPrice($post_object['equipments'], $equipments)['price'];
            $price_insurance = $this->getInsurancePrice($post_object['insurance_plan'], $insurances, $number_day)['price'];

            $total += ($price_equip * $number_day * $number);
            $total += ($price_insurance * $number);

            return [
                'status' => true,
                'price' => convert_price($total),
                'base_price' => $base_price,
                'equipment_price' => $price_equip * $number_day * $number,
                'insurance_price' => $price_insurance * $number
            ];
        }
        return [
            'status' => true,
            'message' => ''
        ];
    }

    public function fetchCarAvailability(Request $request)
    {
        $start_date = strtotime($request->post('startDate'));
        $end_date = strtotime($request->post('endDate'));
        $postID = $request->post('postID');
        $postHashing = $request->get('postHashing');

        $events = [];

        if ($start_date && $end_date && gmz_compare_hashing($postID, $postHashing)) {
            $carAvaiRepo = CarAvailabilityRepository::inst();
            $orderRepo = OrderRepository::inst();
            $avails = $carAvaiRepo->getDataAvailability($postID, $start_date, $end_date);
            $car = $this->repository->find($postID);
            $price = (float)$car->base_price;
            $quantity = (int)$car->quantity;
            $orders = $orderRepo->getOrderItems($postID, $start_date, $end_date, 'car');

            for ($i = $start_date; $i <= $end_date; $i = strtotime('+1 day', $i)) {
                $status = 'available';
                $event = convert_price($price);

                if (!$avails->isEmpty()) {
                    foreach ($avails as $avail) {
                        if ($i >= $avail->check_in && $i <= $avail->check_out) {
                            $event = convert_price($avail->price);
                            if ($avail->status == 'unavailable') {
                                $status = 'unavailable';
                                $event = __('Unavailable');
                            }
                            break;
                        }
                    }
                }

                if (!empty($quantity) && $quantity > 0) {
                    if (!$orders->isEmpty()) {
                        $booked = 0;
                        foreach ($orders as $item) {
                            if ($i >= $item['start_date'] && $i <= $item['end_date']) {
                                $booked += (int)$item['number'];
                            }
                        }
                        if ($booked >= $quantity) {
                            $status = 'unavailable';
                            $event = __('Unavailable');
                        }
                    }
                }

                $events[date('Y_m_d', $i)] = [
                    'start' => date('Y-m-d', $i),
                    'end' => date('Y-m-d', $i),
                    'status' => $status,
                    'event' => $event
                ];
            }
        }

        return [
            'status' => true,
            'events' => $events
        ];
    }

    public function addToCart(Request $request)
    {
        $post_id = $request->post('post_id');
        $post_hashing = $request->post('post_hashing');
        $check_in = $request->post('check_in');
        $check_out = $request->post('check_out');
        $number = $request->post('number');
        $equipment = $request->post('equipment');
        $insurance = $request->post('insurance');

        if (gmz_compare_hashing($post_id, $post_hashing) && $check_in && $check_out && $number > 0) {
            $car_object = $this->repository->find($post_id);
            $check_in_str = strtotime($check_in);
            $check_out_str = strtotime($check_out);

            if ($check_in_str > $check_out_str || empty($check_in_str) || empty($check_out_str)) {
                return [
                    'status' => false,
                    'message' => __('Please select a valid datetime')
                ];
            }

            $number_day = gmz_date_diff($check_in_str, $check_out_str) + 1;

            $equipment_data = $this->getEquipmentPrice($car_object['equipments'], $equipment);
            $insurance_data = $this->getInsurancePrice($car_object['insurance_plan'], $insurance, $number_day);

            $data = [
                'post_id' => $post_id,
                'number' => $number,
                'check_in' => $check_in_str,
                'check_out' => $check_out_str,
                'number_day' => $number_day,
                'equipment_data' => $equipment_data['data'],
                'insurance_data' => $insurance_data['data'],
                'coupon_data' => []
            ];

            if ($number > $car_object['quantity']) {
                return [
                    'status' => false,
                    'message' => __('The quantity need to less than ' . $car_object['quantity'])
                ];
            }

            $price_data = $this->getRealPrice($post_id, $check_in, $check_out, $number, $equipment, $insurance);

            if ($price_data['status']) {
                $base_price = $price_data['base_price'];
                $equipment_price = $price_data['equipment_price'];
                $insurance_price = $price_data['insurance_price'];
                $sub_total = $base_price + $equipment_price + $insurance_price;
                $total = $sub_total;
                $tax = get_tax();
                if ($tax['included'] == 'off') {
                    $total += ($total * $tax['percent'] / 100);
                }

                $cart_data = [
                    'post_id' => $post_id,
                    'post_object' => serialize($car_object),
                    'post_type' => 'car',
                    'base_price' => $base_price,
                    'equipment_price' => $equipment_price,
                    'insurance_price' => $insurance_price,
                    'sub_total' => $sub_total,
                    'tax' => $tax,
                    'coupon' => '',
                    'coupon_percent' => 0,
                    'coupon_value' => 0,
                    'total' => $total,
                    'cart_data' => $data,
                ];

                \Cart::inst()->setCart($cart_data);

                return [
                    'status' => true,
                    'redirect' => url('checkout')
                ];
            } else {
                if ($price_data['message'] != '') {
                    return [
                        'status' => false,
                        'message' => $price_data['message']
                    ];
                }
            }

            return [
                'status' => false,
                'message' => __('Data is invalid')
            ];
        }
    }

    public function getPostBySlug($slug)
    {
        $data = $this->repository->where(['post_slug' => $slug], true);
        if ($data) {
            if (is_seo_enable()) {
                $seoRepo = SeoRepository::inst();
                $seo = $seoRepo->where([
                    'post_id' => $data->id,
                    'post_type' => GMZ_SERVICE_CAR
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

    public function getSearchResult(Request $request)
    {
        $default = [
            'page' => 1,
            'lat' => '0',
            'lng' => '0',
            'address' => '',
            'checkIn' => '',
            'checkOut' => '',
            'startTime' => '',
            'endTime' => '',
            'price_range' => '',
            'car_type' => '',
            'car_feature' => '',
            'car_equipment' => '',
            'number' => intval(get_option('car_search_number', 6)),
            'layout' => 'list',
            'sort' => 'new'
        ];

        $params = gmz_parse_args($request->all(), $default);
        $data = $this->repository->getSearchResult($params);

        $total_result = $data->total();
        $search_str = get_search_string('car', $total_result, $params);
        $location = [];

        if ($params['layout'] == 'list') {
            $html = '';
        } else {
            $html = '<div class="row">';
        }

        if ($total_result > 0) {
            foreach ($data->items() as $key => $val) {
                $img = '';
                if (!empty($val['thumbnail_id'])) {
                    $img = get_attachment_url($val['thumbnail_id'], [360, 240]);
                }

                $item = [
                    'id' => $val['id'],
                    'url' => get_car_permalink($val['post_slug']),
                    'title' => get_translate($val['post_title']),
                    'thumbnail' => $img,
                    'address' => get_translate($val['location_address']),
                    'price' => convert_price($val['base_price']),
                    'lat' => floatval($val['location_lat']),
                    'lng' => floatval($val['location_lng'])
                ];
                array_push($location, $item);
                if ($params['layout'] == 'list') {
                    $html .= view('Frontend::services.car.items.list-item', ['item' => $val])->render();
                } else {
                    $html .= '<div class="col-lg-6 col-md-6 col-sm-12">';
                    $html .= view('Frontend::services.car.items.grid-item', ['item' => $val])->render();
                    $html .= '</div>';
                }
            }
        }

        if ($params['layout'] != 'list') {
            $html .= '</div>';
        }

        Paginator::useBootstrap();

        return [
            'location' => $location,
            'html' => $search_str . $html . $data->links()->toHtml(),
        ];
    }

    public function restoreCar($request)
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
            'post_type' => GMZ_SERVICE_CAR
        ]);
        $commentRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_CAR
        ]);
        $seoRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_CAR
        ]);

        restore_wishlist($post_id, GMZ_SERVICE_CAR);

        return [
            'status' => 1,
            'message' => __('Restore successfully'),
        ];
    }

    public function hardDeleteCar($request)
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
            'post_type' => GMZ_SERVICE_CAR
        ]);

        $commentRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_CAR
        ]);

        $seoRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_CAR
        ]);
        $carAvaiRepo = CarAvailabilityRepository::inst();
        $carAvaiRepo->deleteByWhere([
            'post_id' => $post_id
        ]);

        hard_delete_wishlist($post_id, GMZ_SERVICE_CAR);

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
            'post_type' => GMZ_SERVICE_CAR
        ]);

        $commentRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_CAR
        ]);

        $seoRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_CAR
        ]);

        $this->repository->delete($post_id);

        delete_wishlist($post_id, GMZ_SERVICE_CAR);

        return [
            'status' => 1,
            'message' => __('Delete successfully'),
            'reload' => true
        ];
    }

    public function getPostsPagination($number = 10, $where = [])
    {
        if (is_partner()) {
            $where['author'] = get_current_user_id();
        }
        return $this->repository->paginate($number, $where, true);
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
            'post_title' => 'New car ' . time(),
            'post_slug' => Str::slug('New car ' . time()),
            'author' => get_current_user_id(),
            'status' => 'temp'
        ];

        return $this->repository->save($data);
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
            $isNewSlug = strpos($data['post_slug'], 'new-car-');
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
                    if ($item['type'] == 'term_price') {
                        $data = isset($post_data[$item['id']]) ? $post_data[$item['id']] : [];
                        $term_price_data = [];
                        if (isset($data['price']) && !empty($data['price'])) {
                            foreach ($data['price'] as $key => $val) {
                                $term_price_data[$key] = [
                                    'choose' => isset($data['id'][$key]) ? 'yes' : 'no',
                                    'price' => $val
                                ];
                            }
                        }
                        $post_data[$item['id']] = serialize($term_price_data);
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
        if (isset($post_data['car_type'])) {
            $all_types = get_terms('name', 'car-type', 'id');

            if (!empty($all_types)) {
                $type_in_str = '(' . implode(',', $all_types) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'car' AND term_id IN {$type_in_str}");
            }

            $car_type = $post_data['car_type'];
            if (!empty($car_type)) {
                $data_insert = [
                    'term_id' => $car_type,
                    'post_id' => $post_id,
                    'post_type' => 'car'
                ];
                $termRelationRepo->create($data_insert);
                $post_data['car_type'] = $car_type;
            } else {
                $post_data['car_type'] = '';
            }
        }

        if (isset($post_data['car_feature'])) {
            $all_features = get_terms('name', 'car-feature', 'id');
            if (!empty($all_features)) {
                $feature_in_str = '(' . implode(',', $all_features) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'car' AND term_id IN {$feature_in_str}");
            }

            $car_features = $post_data['car_feature'];

            if (!empty($car_features) && is_array($car_features)) {
                foreach ($car_features as $item) {
                    $data_insert = [
                        'term_id' => $item,
                        'post_id' => $post_id,
                        'post_type' => 'car'
                    ];
                    $termRelationRepo->create($data_insert);
                }
                $post_data['car_feature'] = implode(',', $car_features);
            } else {
                $post_data['car_feature'] = '';
            }
        }

        if (isset($post_data['equipments'])) {
            $all_equips = get_terms('name', 'car-equipment', 'id');
            if (!empty($all_equips)) {
                $equip_in_str = '(' . implode(',', $all_equips) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'car' AND term_id IN {$equip_in_str}");
            }

            $car_equips = maybe_unserialize($post_data['equipments']);
            $car_equip_col = [];
            if (!empty($car_equips)) {
                foreach ($car_equips as $key => $item) {
                    if ($item['choose'] == 'yes') {
                        $car_equip_col[] = $key;
                        $data_insert = [
                            'term_id' => $key,
                            'post_id' => $post_id,
                            'post_type' => 'car'
                        ];
                        $termRelationRepo->create($data_insert);
                    }
                }
                if (!empty($car_equip_col)) {
                    $post_data['car_equipment'] = implode(',', $car_equip_col);
                } else {
                    $post_data['car_equipment'] = '';
                }
            } else {
                $post_data['car_equipment'] = '';
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

            if (isset($post_data['post_content'])) {
                $post_data['post_content'] = balance_tags($post_data['post_content']);
            }

            if (isset($post_data['quantity'])) {
                $post_data['quantity'] = intval($post_data['quantity']);
            }

            if (isset($post_data['passenger']) && empty($post_data['passenger'])) {
                $post_data['passenger'] = 1;
            }

            if (isset($post_data['baggage']) && empty($post_data['baggage'])) {
                $post_data['baggage'] = 1;
            }
            if (isset($post_data['door']) && empty($post_data['door'])) {
                $post_data['door'] = 1;
            }

            if (isset($post_data['base_price'])) {
                $post_data['base_price'] = floatval($post_data['base_price']);
            }

            if (isset($post_data['cancel_before'])) {
                $post_data['cancel_before'] = intval($post_data['cancel_before']);
            }

            $post_data = $this->updateTerm($post_id, $post_data);

            //Status
            if (isset($post_data['status'])) {
                $current_post = get_post($post_id, GMZ_SERVICE_CAR);
                $current_status = $current_post['status'];
                $need_approve = get_option('car_approve', 'off');
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
                    'message' => __('Saving data successfully')
                ];

                if ($request->post('post_slug')) {
                    $response['permalink'] = get_car_permalink($updated['post_slug']);
                }

                $finish = $request->post('finish', '');
                if ($finish) {
                    $response['redirect'] = dashboard_url('edit-car/' . $post_id);
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
            if (is_seo_enable()) {
                $seoRepo = SeoRepository::inst();
                $seo = $seoRepo->where([
                    'post_id' => $post_id,
                    'post_type' => GMZ_SERVICE_CAR
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