<?php

namespace App\Services;

use App\Jobs\SendEnquiryJob;
use App\Repositories\CommentRepository;
use App\Repositories\HotelRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RoomAvailabilityRepository;
use App\Repositories\RoomRepository;
use App\Repositories\SeoRepository;
use App\Repositories\TermRelationRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HotelService extends AbstractService
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
        $this->repository = HotelRepository::inst();
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

        $post_object = get_post($postID, GMZ_SERVICE_HOTEL);
        $postType = get_post_type_by_object($post_object);
        $request->request->add(['post_type' => $postType]);
        if (!empty($post_object)) {

            dispatch(new SendEnquiryJob($request->all(), $post_object));
            //\GMZ_Mail::inst()->sendEmailHotelEnquiry($post_object, $request->all());

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
            $roomAvaiRepo = RoomAvailabilityRepository::inst();
            $avails = $roomAvaiRepo->getDataAvailability($post_id, $check_in, $check_out);

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
            $orders = $orderRepo->getOrderItems($post_id, $check_in, $check_out, GMZ_SERVICE_HOTEL);

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

    public function addToCart(Request $request)
    {
        $hotel_id = $request->post('hotel_id');
        $hotel_hashing = $request->post('hotel_hashing');
        $check_in = $request->post('check_in');
        $check_out = $request->post('check_out');
        $adult = $request->post('adult');
        $children = $request->post('children');
        $rooms = $request->post('room');
        $extra_services = $request->post('extra_service');

        if (gmz_compare_hashing($hotel_id, $hotel_hashing) && $check_in && $check_out && !empty($rooms)) {
            $roomAvaiRepo = RoomAvailabilityRepository::inst();
            $hotel_object = $this->repository->find($hotel_id);
            $check_in_str = strtotime($check_in);
            $check_out_str = strtotime($check_out);

            if ($check_in_str >= $check_out_str || empty($check_in_str) || empty($check_out_str)) {
                return [
                    'status' => false,
                    'message' => __('Please select a valid datetime')
                ];
            }

            //Check availability
            $room_avails = [];
            if (!empty($rooms)) {
                foreach ($rooms as $k => $v) {
                    if ($v['number'] > 0) {
                        $avail = $roomAvaiRepo->checkAvailabilityWithGuest($k, $check_in_str, $check_out_str, $v['number'], $adult, $children);
                        if ($avail->isEmpty()) {
                            $room_avails[] = $k;
                        }
                    }
                }
            }

            if (empty($room_avails)) {
                return [
                    'status' => false,
                    'message' => __('Data is invalid. Please search room again.')
                ];
            }

            $number_day = gmz_date_diff($check_in_str, $check_out_str);

            $base_price = 0;
            $extra_price = 0;
            $number_room = 0;
            $room_prices = [];
            if (!empty($rooms)) {
                foreach ($rooms as $k => $v) {
                    if ($v['number'] > 0 && in_array($k, $room_avails)) {
                        $room = get_post($k, GMZ_SERVICE_ROOM);
                        $price = get_room_price($room, $check_in, $check_out);
                        $number_room += (int)$v['number'];
                        $base_price += $price * $v['number'];
                        $room_prices[$k] = [
                            'number' => (int)$v['number'],
                            'price' => $price
                        ];
                    }
                }
            }

            $extra_data = [];
            if (!empty($extra_services)) {
                $extras = maybe_unserialize($hotel_object['extra_services']);
                if (!empty($extras)) {
                    foreach ($extra_services as $k => $v) {
                        if (isset($extras[$v])) {
                            $extra_data[$v] = $extras[$v];
                            $extra_price += ((float)$extras[$v]['price'] * $number_room * $number_day);
                        }
                    }
                }
            }

            $data = [
                'post_id' => $hotel_id,
                'number' => $number_room,
                'check_in' => $check_in_str,
                'check_out' => $check_out_str,
                'number_day' => $number_day,
                'adult' => $adult,
                'children' => $children,
                'rooms' => $room_prices,
                'extras' => $extra_data,
                'coupon_data' => []
            ];

            $sub_total = $base_price + $extra_price;
            $total = $sub_total;
            $tax = get_tax();
            if ($tax['included'] == 'off') {
                $total += ($total * $tax['percent'] / 100);
            }

            $cart_data = [
                'post_id' => $hotel_id,
                'post_object' => serialize($hotel_object),
                'post_type' => GMZ_SERVICE_HOTEL,
                'base_price' => $base_price,
                'extra_price' => $extra_price,
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
        }
        return [
            'status' => false,
            'message' => __('Data is invalid')
        ];
    }

    public function getPostBySlug($slug)
    {
        $data = $this->repository->where(['post_slug' => $slug], true);
        if ($data) {
            if (is_seo_enable()) {
                $seoRepo = SeoRepository::inst();
                $seo = $seoRepo->where([
                    'post_id' => $data->id,
                    'post_type' => GMZ_SERVICE_HOTEL
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
            'price_range' => '',
            'property_type' => '',
            'hotel_facilities' => '',
            'hotel_services' => '',
            'hotel_star' => '',
            'number_room' => 1,
            'adult' => 1,
            'children' => 0,
            'id_unavailable' => '',
            'number' => intval(get_option('hotel_search_number', 6)),
            'layout' => 'list',
            'sort' => 'new'
        ];

        $params = gmz_parse_args($request->all(), $default);

        $hotel_unavailable = $this->getListHotelsUnavaible($params['checkIn'], $params['checkOut'], $params['number_room'], $params['adult'], $params['children']);
        $params['id_unavailable'] = $hotel_unavailable;

        $data = $this->repository->getSearchResult($params);

        $total_result = $data->total();
        $search_str = get_search_string('hotel', $total_result, $params);
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
                    'url' => get_hotel_permalink($val['post_slug']),
                    'title' => get_translate($val['post_title']),
                    'thumbnail' => $img,
                    'address' => get_translate($val['location_address']),
                    'price' => convert_price($val['base_price']),
                    'lat' => floatval($val['location_lat']),
                    'lng' => floatval($val['location_lng'])
                ];
                array_push($location, $item);

                if ($params['layout'] == 'list') {
                    $html .= view('Frontend::services.hotel.items.list-item', ['item' => $val])->render();
                } else {
                    $html .= '<div class="col-lg-6 col-md-6 col-sm-12">';
                    $html .= view('Frontend::services.hotel.items.grid-item', ['item' => $val])->render();
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

    public function getListHotelsUnavaible($start, $end, $number_room, $number_adult, $number_child)
    {
        $start = strtotime($start);
        $end = strtotime($end);
        $roomAvaiRepo = RoomAvailabilityRepository::inst();
        $list_hotel = $roomAvaiRepo->getHotelUnavailable($start, $end, $number_room, $number_adult, $number_child);

        $r = [];
        $arr_num_room = [];
        if (!empty($list_hotel)) {
            foreach ($list_hotel as $k => $v) {
                if (isset($r[$v['hotel_id']])) {
                    if (!in_array($v['post_id'], $r[$v['hotel_id']])) {
                        $r[$v['hotel_id']][] = $v['post_id'];
                    }
                } else {
                    $r[$v['hotel_id']][] = $v['post_id'];
                    $arr_num_room[$v['hotel_id']] = $v['total_room'];
                }
            }
        }

        $id_unavailable = [];
        if (!empty($r)) {
            foreach ($r as $k => $v) {
                if (!empty($v) && count($v) == $arr_num_room[$k]) {
                    $id_unavailable[] = $k;
                }
            }
        }

        return $id_unavailable;
    }

    public function restoreHotel($request)
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
            'post_type' => GMZ_SERVICE_HOTEL
        ]);
        $commentRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_HOTEL
        ]);
        $seoRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_HOTEL
        ]);

        restore_wishlist($post_id, GMZ_SERVICE_HOTEL);

        return [
            'status' => 1,
            'message' => __('Restore successfully'),
        ];
    }

    public function hardDeleteHotel($request)
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

        $roomRepo = RoomRepository::inst();
        $roomAvaiRepo = RoomAvailabilityRepository::inst();
        $termRelationRepo = TermRelationRepository::inst();
        $commentRepo = CommentRepository::inst();
        $seoRepo = SeoRepository::inst();

        $termRelationRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_HOTEL
        ]);

        $commentRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_HOTEL
        ]);

        $seoRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_HOTEL
        ]);

        $roomAvaiRepo->deleteByWhere([
            'hotel_id' => $post_id
        ]);

        $rooms = $roomRepo->where([
            'hotel_id' => $post_id
        ]);
        if (!$rooms->isEmpty()) {
            foreach ($rooms as $room) {
                $termRelationRepo->hardDeleteByWhere([
                    'post_id' => $room['id'],
                    'post_type' => GMZ_SERVICE_ROOM
                ]);
            }
        }

        $roomRepo->hardDeleteByWhere([
            'hotel_id' => $post_id
        ]);

        hard_delete_wishlist($post_id, GMZ_SERVICE_HOTEL);

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
            'post_type' => GMZ_SERVICE_HOTEL
        ]);

        $commentRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_HOTEL
        ]);

        $seoRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_HOTEL
        ]);

        $this->repository->delete($post_id);

        delete_wishlist($post_id, GMZ_SERVICE_HOTEL);

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
            'post_title' => 'New hotel ' . time(),
            'post_slug' => Str::slug('New hotel ' . time()),
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
            $isNewSlug = strpos($data['post_slug'], 'new-hotel-');
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
        if (isset($post_data['property_type'])) {
            $all_types = get_terms('name', 'property-type', 'id');

            if (!empty($all_types)) {
                $type_in_str = '(' . implode(',', $all_types) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'hotel' AND term_id IN {$type_in_str}");
            }

            $property_type = $post_data['property_type'];
            if (!empty($property_type)) {
                $data_insert = [
                    'term_id' => $property_type,
                    'post_id' => $post_id,
                    'post_type' => GMZ_SERVICE_HOTEL
                ];
                $termRelationRepo->create($data_insert);
                $post_data['property_type'] = $property_type;
            } else {
                $post_data['property_type'] = '';
            }
        }

        if (isset($post_data['hotel_facilities'])) {
            $all_facilities = get_terms('name', 'hotel-facilities', 'id');
            if (!empty($all_facilities)) {
                $facility_in_str = '(' . implode(',', $all_facilities) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'hotel' AND term_id IN {$facility_in_str}");
            }

            $hotel_facilities = $post_data['hotel_facilities'];

            if (!empty($hotel_facilities) && is_array($hotel_facilities)) {
                foreach ($hotel_facilities as $item) {
                    $data_insert = [
                        'term_id' => $item,
                        'post_id' => $post_id,
                        'post_type' => GMZ_SERVICE_HOTEL
                    ];
                    $termRelationRepo->create($data_insert);
                }
                $post_data['hotel_facilities'] = implode(',', $hotel_facilities);
            } else {
                $post_data['hotel_facilities'] = '';
            }
        }

        if (isset($post_data['hotel_services'])) {
            $all_services = get_terms('name', 'hotel-services', 'id');
            if (!empty($all_services)) {
                $service_in_str = '(' . implode(',', $all_services) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'hotel' AND term_id IN {$service_in_str}");
            }

            $hotel_services = $post_data['hotel_services'];

            if (!empty($hotel_services) && is_array($hotel_services)) {
                foreach ($hotel_services as $item) {
                    $data_insert = [
                        'term_id' => $item,
                        'post_id' => $post_id,
                        'post_type' => GMZ_SERVICE_HOTEL
                    ];
                    $termRelationRepo->create($data_insert);
                }
                $post_data['hotel_services'] = implode(',', $hotel_services);
            } else {
                $post_data['hotel_services'] = '';
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

            $post_data = $this->updateTerm($post_id, $post_data);

            //Status
            if (isset($post_data['status'])) {
                $current_post = get_post($post_id, GMZ_SERVICE_HOTEL);
                $current_status = $current_post['status'];
                $need_approve = get_option('hotel_approve', 'off');
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
                    $response['permalink'] = get_hotel_permalink($updated['post_slug']);
                }

                $finish = $request->post('finish', '');
                if ($finish) {
                    $response['redirect'] = dashboard_url('edit-hotel/' . $post_id);
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
                    'post_type' => GMZ_SERVICE_HOTEL
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