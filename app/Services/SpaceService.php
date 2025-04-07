<?php

namespace App\Services;

use App\Jobs\SendEnquiryJob;
use App\Repositories\CommentRepository;
use App\Repositories\OrderRepository;
use App\Repositories\SeoRepository;
use App\Repositories\SpaceAvailabilityRepository;
use App\Repositories\SpaceRepository;
use App\Repositories\TermRelationRepository;
use App\Repositories\TermRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SpaceService extends AbstractService
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
        $this->repository = SpaceRepository::inst();
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

    public function fetchTime(Request $request)
    {
        $start_date = strtotime($request->post('startDate'));
        $postID = $request->post('postID');
        $postHashing = $request->get('postHashing');

        $list_times = get_list_time();
        if ($start_date && gmz_compare_hashing($postID, $postHashing)) {
            $orderRepo = OrderRepository::inst();
            $orderData = $orderRepo->getOrderItems($postID, $start_date, $start_date, GMZ_SERVICE_SPACE);
            if (!$orderData->isEmpty()) {
                foreach ($orderData as $item) {
                    $start_time = $item['start_time'];
                    $end_time = $item['end_time'];
                    for ($i = $start_time; $i <= $end_time; $i = strtotime('+30 minutes', $i)) {
                        $exists_time = date(get_time_format(), $i);
                        if (isset($list_times[$exists_time])) {
                            unset($list_times[$exists_time]);
                        }
                    }
                }
            }
        }

        $list_times = array_merge(['' => __('Start Time')], $list_times);

        return [
            'status' => true,
            'list_times' => view('Frontend::components.ajax.booking-time', ['list_times' => $list_times])->render()
        ];
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

        $post_object = get_post($postID, GMZ_SERVICE_SPACE);
        $postType = get_post_type_by_object($post_object);
        $request->request->add(['post_type' => $postType]);
        if (!empty($post_object)) {

            dispatch(new SendEnquiryJob($request->all(), $post_object));
            //\GMZ_Mail::inst()->sendEmailSpaceEnquiry($post_object, $request->all());

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

    private function getExtraPrice($extras, $post_data)
    {
        $total = 0;
        $data = [];
        if (!empty($post_data)) {
            $extras = maybe_unserialize($extras);
            if (!empty($extras)) {
                foreach ($extras as $key => $val) {
                    if ($val['required'] == 'on') {
                        $total += intval($val['price']);
                        $data[$key] = $val;
                    } else {
                        if (in_array($key, $post_data)) {
                            $total += intval($val['price']);
                            $data[$key] = $val;
                        }
                    }
                }
            }
        }

        return [
            'price' => $total,
            'data' => $data
        ];
    }

    public function getRealPrice($post_id, $check_in, $check_out, $extras)
    {
        $check_in = strtotime($check_in);
        $check_out = strtotime($check_out);

        if ($check_in < strtotime(date('Y-m-d'))) {
            return [
                'status' => false,
                'message' => __('Date is invalid')
            ];
        }

        $post_object = $this->repository->find($post_id);
        $booking_type = $post_object->booking_type;
        $price = (float)$post_object->base_price;

        $total = 0;

        $spaceAvaiRepo = SpaceAvailabilityRepository::inst();
        $orderRepo = OrderRepository::inst();
        if ($booking_type == 'per_day') {
            $has_discount_by_day = false;
            $number_day = gmz_date_diff($check_in, $check_out) + 1;
            $discount_by_days = $post_object['discount_by_day'];
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

            $total_avail = 0;
            $check_available = true;

            $avails = $spaceAvaiRepo->getDataAvailability($post_id, $check_in, $check_out);

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

            $orders = $orderRepo->getOrderItems($post_id, $check_in, $check_out, 'space');

            if (!$orders->isEmpty()) {
                for ($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)) {
                    foreach ($orders as $item) {
                        if ($i >= $item['start_date'] && $i <= $item['end_date']) {
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
        } else {
            $avails = $spaceAvaiRepo->getDataAvailability($post_id, $check_in, $check_out);
            if (!$avails->isEmpty()) {
                if ($avails[0]['status'] == 'unavailable') {
                    return [
                        'status' => false,
                        'message' => __('Date is not available')
                    ];
                } else {
                    $price = $avails[0]['price'];
                }
            }

            $startTime = strtotime(\request()->post('startTime'));
            $endTime = strtotime(\request()->post('endTime'));

            $orders = $orderRepo->getOrderItemsWithTime($post_id, $check_in, $check_out, $startTime, $endTime, 'space', $booking_type);

            if (!$orders->isEmpty()) {
                return [
                    'status' => false,
                    'message' => __('Time range is not available')
                ];
            }

            $number_hour = ceil(($endTime - $startTime) / 3600);
            $total = $price * $number_hour;
        }

        $base_price = $total;
        $price_extra = $this->getExtraPrice($post_object['extra_services'], $extras)['price'];

        if ($booking_type == 'per_day') {
            $price_extra = $price_extra * $number_day;
        } else {
            $price_extra = $price_extra * $number_hour;
        }

        $total += $price_extra;

        return [
            'status' => true,
            'price' => convert_price($total),
            'base_price' => $base_price,
            'extra_price' => $price_extra
        ];
    }

    public function fetchSpaceAvailability(Request $request)
    {
        $start_date = strtotime($request->post('startDate'));
        $end_date = strtotime($request->post('endDate'));
        $postID = $request->post('postID');
        $postHashing = $request->get('postHashing');
        $bookingType = $request->get('bookingType');

        $events = [];

        if ($start_date && $end_date && gmz_compare_hashing($postID, $postHashing)) {
            $spaceAvaiRepo = SpaceAvailabilityRepository::inst();
            $orderRepo = OrderRepository::inst();
            $avails = $spaceAvaiRepo->getDataAvailability($postID, $start_date, $end_date);
            $space = $this->repository->find($postID);
            $price = (float)$space->base_price;
            if ($bookingType == 'per_day') {
                $orders = $orderRepo->getOrderItems($postID, $start_date, $end_date, GMZ_SERVICE_SPACE);
            } else {
                $orders = $orderRepo->getOrderItemsGroupDate($postID, $start_date, $end_date, GMZ_SERVICE_SPACE);
            }

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
                if (!$orders->isEmpty()) {
                    if ($bookingType == 'per_day') {
                        foreach ($orders as $item) {
                            if ($i >= $item['start_date'] && $i <= $item['end_date']) {
                                $status = 'unavailable';
                                $event = __('Unavailable');
                            }
                        }
                    } else {
                        foreach ($orders as $item) {
                            if ($i == $item['start_date']) {
                                $number_minute = $item['number_minute'];
                                $count_booking = $item['count_booking'];
                                if ($number_minute + ($count_booking * 60) == 1440) {
                                    $status = 'unavailable';
                                    $event = __('Unavailable');
                                }
                            }
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
        $check_out = $request->post('check_out', $check_in);
        $start_time = $request->post('startTime');
        $end_time = $request->post('endTime');
        $adult = $request->post('adult', 1);
        $children = $request->post('children', 0);
        $infant = $request->post('infant', 0);
        $extra = $request->post('extras');

        if (gmz_compare_hashing($post_id, $post_hashing) && $check_in && $check_out) {
            $space_object = $this->repository->find($post_id);
            $booking_type = $space_object['booking_type'];
            $check_in_str = strtotime($check_in);
            $check_out_str = strtotime($check_out);

            if ($check_in_str > $check_out_str || empty($check_in_str) || empty($check_out_str)) {
                return [
                    'status' => false,
                    'message' => __('Please select a valid datetime')
                ];
            }

            $start_time_str = time();
            $end_time_str = time();

            if ($booking_type == 'per_hour') {
                $start_time_str = strtotime($start_time);
                $end_time_str = strtotime($end_time);
                if ($start_time_str > $end_time_str || empty($start_time_str) || empty($end_time_str)) {
                    return [
                        'status' => false,
                        'message' => __('Please select a valid time')
                    ];
                }
            }

            if (intval($adult) + intval($children) > $space_object['number_of_guest']) {
                return [
                    'status' => false,
                    'message' => sprintf(__('The maximum number of guests is %s'), $space_object['number_of_guest'])
                ];
            }

            $number_hour = 0;
            $number_day = gmz_date_diff($check_in_str, $check_out_str) + 1;
            if ($booking_type == 'per_hour') {
                $number_hour = ceil(($end_time_str - $start_time_str) / 3600);
            }

            $extra_data = $this->getExtraPrice($space_object['extra_services'], $extra);

            $data = [
                'post_id' => $post_id,
                'check_in' => $check_in_str,
                'check_out' => $check_out_str,
                'start_time' => $start_time_str,
                'end_time' => $end_time_str,
                'number_day' => $number_day,
                'number_hour' => $number_hour,
                'booking_type' => $booking_type,
                'extra_data' => $extra_data['data'],
                'coupon_data' => []
            ];

            $price_data = $this->getRealPrice($post_id, $check_in, $check_out, $extra);

            if ($price_data['status']) {
                $base_price = $price_data['base_price'];
                $extra_price = $price_data['extra_price'];
                $sub_total = $base_price + $extra_price;
                $total = $sub_total;
                $tax = get_tax();
                if ($tax['included'] == 'off') {
                    $total += ($total * $tax['percent'] / 100);
                }

                $cart_data = [
                    'post_id' => $post_id,
                    'post_object' => serialize($space_object),
                    'post_type' => 'space',
                    'base_price' => $base_price,
                    'extra_price' => $extra_price,
                    'sub_total' => $sub_total,
                    'tax' => $tax,
                    'coupon' => '',
                    'coupon_percent' => 0,
                    'coupon_value' => 0,
                    'total' => $total,
                    'adult' => $adult,
                    'children' => $children,
                    'infant' => $infant,
                    'cart_data' => $data,
                ];

                \Cart::inst()->setCart($cart_data);

                return [
                    'status' => true,
                    'redirect' => url('checkout'),
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
                    'post_type' => GMZ_SERVICE_SPACE
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
            'checkInTime' => '',
            'checkOut' => '',
            'checkOutTime' => '',
            'startTime' => '',
            'endTime' => '',
            'price_range' => '',
            'space_type' => '',
            'space_amenity' => '',
            'adult' => '',
            'children' => '',
            'infant' => '',
            'bookingType' => '',
            'number' => intval(get_option('space_search_number', 6)),
            'layout' => 'list',
            'sort' => 'new'
        ];

        $params = gmz_parse_args($request->all(), $default);
        $data = $this->repository->getSearchResult($params);

        $total_result = $data->total();
        $search_str = get_search_string('space', $total_result, $params);
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
                    'url' => get_space_permalink($val['post_slug']),
                    'title' => get_translate($val['post_title']),
                    'thumbnail' => $img,
                    'address' => get_translate($val['location_address']),
                    'price' => convert_price($val['base_price']),
                    'lat' => floatval($val['location_lat']),
                    'lng' => floatval($val['location_lng'])
                ];
                array_push($location, $item);

                if ($params['layout'] == 'list') {
                    $html .= view('Frontend::services.space.items.list-item', ['item' => $val])->render();
                } else {
                    $html .= '<div class="col-lg-6 col-md-6 col-sm-12">';
                    $html .= view('Frontend::services.space.items.grid-item', ['item' => $val])->render();
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

    public function restoreSpace($request)
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
            'post_type' => GMZ_SERVICE_SPACE
        ]);
        $commentRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_SPACE
        ]);
        $seoRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_SPACE
        ]);

        restore_wishlist($post_id, GMZ_SERVICE_SPACE);

        return [
            'status' => 1,
            'message' => __('Restore successfully'),
        ];
    }

    public function hardDeleteSpace($request)
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

        $spaceAvaiRepo = SpaceAvailabilityRepository::inst();
        $termRelationRepo = TermRelationRepository::inst();
        $commentRepo = CommentRepository::inst();
        $seoRepo = SeoRepository::inst();

        $termRelationRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_SPACE
        ]);

        $commentRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_SPACE
        ]);

        $seoRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_SPACE
        ]);

        $spaceAvaiRepo->deleteByWhere([
            'post_id' => $post_id
        ]);

        hard_delete_wishlist($post_id, GMZ_SERVICE_SPACE);

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
            'post_type' => GMZ_SERVICE_SPACE
        ]);

        $commentRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_SPACE
        ]);

        $seoRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_SPACE
        ]);

        $this->repository->delete($post_id);

        delete_wishlist($post_id, GMZ_SERVICE_SPACE);

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
            'post_title' => 'New space ' . time(),
            'post_slug' => Str::slug('New space ' . time()),
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
            $isNewSlug = strpos($data['post_slug'], 'new-space-');
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
        if (isset($post_data['space_type'])) {
            $all_types = get_terms('name', 'space-type', 'id');

            if (!empty($all_types)) {
                $type_in_str = '(' . implode(',', $all_types) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'space' AND term_id IN {$type_in_str}");
            }

            $space_type = $post_data['space_type'];
            if (!empty($space_type)) {
                $data_insert = [
                    'term_id' => $space_type,
                    'post_id' => $post_id,
                    'post_type' => 'space'
                ];
                $termRelationRepo->create($data_insert);
                $post_data['space_type'] = $space_type;
            } else {
                $post_data['space_type'] = '';
            }
        }

        if (isset($post_data['space_amenity'])) {

            $all_amenities = get_terms('name', 'space-amenity', 'id');
            if (!empty($all_amenities)) {
                $amenity_in_str = '(' . implode(',', $all_amenities) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'space' AND term_id IN {$amenity_in_str}");
            }

            $space_amenities = $post_data['space_amenity'];

            if (!empty($space_amenities) && is_array($space_amenities)) {
                foreach ($space_amenities as $item) {
                    $data_insert = [
                        'term_id' => $item,
                        'post_id' => $post_id,
                        'post_type' => 'space'
                    ];
                    $termRelationRepo->create($data_insert);
                }
                $post_data['space_amenity'] = implode(',', $space_amenities);
            } else {
                $post_data['space_amenity'] = '';
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

            if (isset($post_data['number_of_guest']) && empty($post_data['number_of_guest'])) {
                $post_data['number_of_guest'] = 1;
            }
            if (isset($post_data['number_of_bedroom']) && empty($post_data['number_of_bedroom'])) {
                $post_data['number_of_bedroom'] = 1;
            }
            if (isset($post_data['number_of_bathroom']) && empty($post_data['number_of_bathroom'])) {
                $post_data['number_of_bathroom'] = 1;
            }

            if (isset($post_data['size'])) {
                $post_data['size'] = floatval($post_data['size']);
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
                $current_post = get_post($post_id, GMZ_SERVICE_SPACE);
                $current_status = $current_post['status'];
                $need_approve = get_option('space_approve', 'off');
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
                    $response['permalink'] = get_space_permalink($updated['post_slug']);
                }

                $finish = $request->post('finish', '');
                if ($finish) {
                    $response['redirect'] = dashboard_url('edit-space/' . $post_id);
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
                    'post_type' => GMZ_SERVICE_SPACE
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