<?php

namespace App\Services;

use App\Jobs\SendEnquiryJob;
use App\Repositories\CommentRepository;
use App\Repositories\OrderRepository;
use App\Repositories\SeoRepository;
use App\Repositories\TermRelationRepository;
use App\Repositories\TermRepository;
use App\Repositories\TourAvailabilityRepository;
use App\Repositories\TourRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use TorMorten\Eventy\Facades\Eventy;

class TourService extends AbstractService
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
        $this->repository = TourRepository::inst();
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
            $orderData = $orderRepo->getOrderItems($postID, $start_date, $start_date, GMZ_SERVICE_TOUR);
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

        $post_object = get_post($postID, GMZ_SERVICE_TOUR);
        $postType = get_post_type_by_object($post_object);
        $request->request->add(['post_type' => $postType]);
        if (!empty($post_object)) {

            dispatch(new SendEnquiryJob($request->all(), $post_object));
            //\GMZ_Mail::inst()->sendEmailTourEnquiry($post_object, $request->all());

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

    public function getRealPrice($post_id, $check_in, $check_out, $extras, $adult, $children, $infant)
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
        $total = 0;

        if($booking_type != 'package') {
            $tourAvaiRepo = TourAvailabilityRepository::inst();
            $avails = $tourAvaiRepo->getDataAvailability($post_id, $check_in, $check_out);

            if ($avails) {
                $group_size_avail = (int)$avails['group_size'];
                $slot_avail = $group_size_avail - $avails['booked'];
                $slot_select = $adult + $children;
                $filterCheckAvail = Eventy::filter('gmz_before_check_availability', ['status' => 'next'], $post_id, $slot_select, $check_in, $avails);
                if ($filterCheckAvail['status'] === false) {
                    return $filterCheckAvail;
                } elseif ($filterCheckAvail['status'] === 'next') {
                    if ($avails['status'] == 'unavailable' || $slot_avail <= 0) {
                        return [
                            'status' => false,
                            'message' => __('Tour is not available')
                        ];
                    } elseif ($slot_select > $slot_avail) {
                        return [
                            'status' => false,
                            'message' => sprintf(__('This tour has only %s slots left'), $slot_avail)
                        ];
                    }
                }
                $adult_price_avail = (float)$avails['adult_price'];
                $children_price_avail = (float)$avails['children_price'];
                $infant_price_avail = (float)$avails['infant_price'];

                $total += ($adult_price_avail * $adult + $children_price_avail * $children + $infant_price_avail * $infant);
            } else {
                return [
                    'status' => false,
                    'message' => __('Tour is not available')
                ];
            }
        }else{
            $adult_price = (float)$post_object->adult_price;
            $children_price = (float)$post_object->children_price;
            $infant_price = (float)$post_object->infant_price;
            $total += ($adult_price * $adult + $children_price * $children + $infant_price * $infant);
        }

        $base_price = $total;
        $price_extra = $this->getExtraPrice($post_object['extra_services'], $extras)['price'];
        $total += $price_extra;

        return [
            'status' => true,
            'price' => convert_price($total),
            'base_price' => $base_price,
            'extra_price' => $price_extra
        ];
    }

    public function fetchTourAvailability(Request $request)
    {
        $start_date = strtotime($request->post('startDate'));
        $end_date = strtotime($request->post('endDate'));
        $postID = $request->post('postID');
        $postHashing = $request->get('postHashing');
        $bookingType = $request->get('bookingType');

        $events = [];

        if ($start_date && $end_date && gmz_compare_hashing($postID, $postHashing)) {
            $tourAvaiRepo = TourAvailabilityRepository::inst();
            $avails = $tourAvaiRepo->getDataAvailabilityForCalendar($postID, $start_date, $end_date);

            $tour = $this->repository->find($postID);
            $adult_price = (float)$tour->adult_price;
            $children_price = (float)$tour->children_price;
            $infant_price = (float)$tour->infant_price;

            for ($i = $start_date; $i <= $end_date; $i = strtotime('+1 day', $i)) {
                $status = 'available';
                $event = sprintf(__('Adult: %s'), convert_price($adult_price)) . '<br />';
                $event .= sprintf(__('Child: %s'), convert_price($children_price)) . '<br />';
                $event .= sprintf(__('Infant: %s'), convert_price($infant_price));
                if (!$avails->isEmpty()) {
                    foreach ($avails as $avail) {
                        if ($i >= $avail->check_in && $i <= $avail->check_out) {
                            if ($avail->status == 'unavailable') {
                                $status = 'unavailable';
                                $event = __('Unavailable');
                            } elseif ($avail->booked >= $avail->group_size) {
                                $status = 'unavailable';
                                $event = __('Booked');
                            } else {
                                $status = 'available';
                                $event = sprintf(__('Adult: %s'), convert_price($avail->adult_price)) . '<br />';
                                $event .= sprintf(__('Child: %s'), convert_price($avail->children_price)) . '<br />';
                                $event .= sprintf(__('Infant: %s'), convert_price($avail->infant_price));
                            }
                            break;
                        }else{
                            $status = 'unavailable';
                            $event = __('Unavailable');
                        }
                    }
                }else{
                    $status = 'unavailable';
                    $event = __('Unavailable');
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
        $adult = $request->post('adult', 1);
        $children = $request->post('children', 0);
        $infant = $request->post('infant', 0);
        $extra = $request->post('extras');

        if (gmz_compare_hashing($post_id, $post_hashing) && $check_in && $check_out) {
            $tour_object = $this->repository->find($post_id);
            $check_in_str = strtotime($check_in);
            $check_out_str = strtotime($check_out);

            if (empty($check_in_str)) {
                return [
                    'status' => false,
                    'message' => __('Please select a valid datetime')
                ];
            }

            $customAction = Eventy::action('gmz_tour_add_to_cart_action', [], $tour_object, $request->all());
            if (!empty($customAction)) {
                return $customAction;
            }

            $price_data = $this->getRealPrice($post_id, $check_in, $check_out, $extra, $adult, $children, $infant);

            $extra_data = $this->getExtraPrice($tour_object['extra_services'], $extra);

            $data = [
                'post_id' => $post_id,
                'check_in' => $check_in_str,
                'check_out' => $check_out_str,
                'extra_data' => $extra_data['data'],
                'coupon_data' => []
            ];

            if ($price_data['status']) {
                $base_price = $price_data['base_price'];
                $extra_price = $price_data['extra_price'];
                $sub_total = $base_price + $extra_price;
                $total = $sub_total;

                $total = Eventy::filter('gmz_tour_before_tax', $total, $tour_object['discount_offer']);

                $tax = get_tax();
                if ($tax['included'] == 'off') {
                    $total += ($total * $tax['percent'] / 100);
                }

                $cart_data = Eventy::filter('gmz_tour_add_cart_data', [
                    'post_id' => $post_id,
                    'post_object' => serialize($tour_object),
                    'post_type' => 'tour',
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
                ], $request->all());

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
                    'post_type' => GMZ_SERVICE_TOUR
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
            'tour_type' => '',
            'tour_include' => '',
            'tour_exclude' => '',
            'adult' => '',
            'children' => '',
            'infant' => '',
            'number' => intval(get_option('tour_search_number', 6)),
            'unavailable_id' => '',
            'layout' => 'list',
            'sort' => 'new'
        ];

        $params = gmz_parse_args($request->all(), $default);

        if (!empty($params['checkIn']) && !empty($params['checkOut'])) {
            $tourAvaiRepo = TourAvailabilityRepository::inst();
            $unavail = $tourAvaiRepo->getListUnavailable($params);
            $params['unavailable_id'] = $unavail;
        }

        $data = $this->repository->getSearchResult($params);

        $total_result = $data->total();
        $search_str = get_search_string('tour', $total_result, $params);
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
                    'url' => get_tour_permalink($val['post_slug']),
                    'title' => get_translate($val['post_title']),
                    'thumbnail' => $img,
                    'address' => get_translate($val['location_address']),
                    'price' => convert_price($val['adult_price']),
                    'lat' => floatval($val['location_lat']),
                    'lng' => floatval($val['location_lng'])
                ];
                array_push($location, $item);

                if ($params['layout'] == 'list') {
                    $html .= view('Frontend::services.tour.items.list-item', ['item' => $val])->render();
                } else {
                    $html .= '<div class="col-lg-6 col-md-6 col-sm-12">';
                    $html .= view('Frontend::services.tour.items.grid-item', ['item' => $val])->render();
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

    public function restoreTour($request)
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
            'post_type' => GMZ_SERVICE_TOUR
        ]);
        $commentRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_TOUR
        ]);
        $seoRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_TOUR
        ]);

        restore_wishlist($post_id, GMZ_SERVICE_TOUR);

        return [
            'status' => 1,
            'message' => __('Restore successfully'),
        ];
    }

    public function hardDeleteTour($request)
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

        $tourAvaiRepo = TourAvailabilityRepository::inst();
        $termRelationRepo = TermRelationRepository::inst();
        $commentRepo = CommentRepository::inst();
        $seoRepo = SeoRepository::inst();

        $termRelationRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_TOUR
        ]);

        $commentRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_TOUR
        ]);

        $seoRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_TOUR
        ]);

        $tourAvaiRepo->deleteByWhere([
            'post_id' => $post_id
        ]);

        hard_delete_wishlist($post_id, GMZ_SERVICE_TOUR);

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
            'post_type' => GMZ_SERVICE_TOUR
        ]);

        $commentRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_TOUR
        ]);

        $seoRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_TOUR
        ]);

        $this->repository->delete($post_id);

        delete_wishlist($post_id, GMZ_SERVICE_TOUR);

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
            'post_title' => 'New tour ' . time(),
            'post_slug' => Str::slug('New tour ' . time()),
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
            $isNewSlug = strpos($data['post_slug'], 'new-tour-');
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
        if (isset($post_data['tour_type'])) {
            $all_types = get_terms('name', 'tour-type', 'id');

            if (!empty($all_types)) {
                $type_in_str = '(' . implode(',', $all_types) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'tour' AND term_id IN {$type_in_str}");
            }

            $tour_type = $post_data['tour_type'];
            if (!empty($tour_type)) {
                $data_insert = [
                    'term_id' => $tour_type,
                    'post_id' => $post_id,
                    'post_type' => 'tour'
                ];
                $termRelationRepo->create($data_insert);
                $post_data['tour_type'] = $tour_type;
            } else {
                $post_data['tour_type'] = '';
            }
        }

        if (isset($post_data['tour_include'])) {
            $all_includes = get_terms('name', 'tour-include', 'id');
            if (!empty($all_includes)) {
                $include_in_str = '(' . implode(',', $all_includes) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'tour' AND term_id IN {$include_in_str}");
            }

            $tour_includes = $post_data['tour_include'];

            if (!empty($tour_includes) && is_array($tour_includes)) {
                foreach ($tour_includes as $item) {
                    $data_insert = [
                        'term_id' => $item,
                        'post_id' => $post_id,
                        'post_type' => 'tour'
                    ];
                    $termRelationRepo->create($data_insert);
                }
                $post_data['tour_include'] = implode(',', $tour_includes);
            } else {
                $post_data['tour_include'] = '';
            }
        }

        if (isset($post_data['tour_exclude'])) {
            $all_excludes = get_terms('name', 'tour-exclude', 'id');
            if (!empty($all_excludes)) {
                $exclude_in_str = '(' . implode(',', $all_excludes) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'tour' AND term_id IN {$exclude_in_str}");
            }

            $tour_excludes = $post_data['tour_exclude'];

            if (!empty($tour_excludes) && is_array($tour_excludes)) {
                foreach ($tour_excludes as $item) {
                    $data_insert = [
                        'term_id' => $item,
                        'post_id' => $post_id,
                        'post_type' => 'tour'
                    ];
                    $termRelationRepo->create($data_insert);
                }
                $post_data['tour_exclude'] = implode(',', $tour_excludes);
            } else {
                $post_data['tour_exclude'] = '';
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

            if (isset($post_data['group_size']) && empty($post_data['group_size'])) {
                $post_data['group_size'] = 1;
            }

            if (isset($post_data['adult_price'])) {
                $post_data['adult_price'] = floatval($post_data['adult_price']);
            }

            if (isset($post_data['children_price'])) {
                $post_data['children_price'] = floatval($post_data['children_price']);
            }

            if (isset($post_data['infant_price'])) {
                $post_data['infant_price'] = floatval($post_data['infant_price']);
            }

            if (isset($post_data['cancel_before'])) {
                $post_data['cancel_before'] = intval($post_data['cancel_before']);
            }

            Eventy::action('gmz_save_tour', $post_id, $post_data);

            $post_data = $this->updateTerm($post_id, $post_data);

            //Status
            if (isset($post_data['status'])) {
                $current_post = get_post($post_id, GMZ_SERVICE_TOUR);
                $current_status = $current_post['status'];
                $need_approve = get_option('tour_approve', 'off');
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
                    'message' => __('Saving data successfully')
                ];
                if ($request->post('post_slug')) {
                    $response['permalink'] = get_tour_permalink($updated['post_slug']);
                }

                $finish = $request->post('finish', '');
                if ($finish) {
                    $response['redirect'] = dashboard_url('edit-tour/' . $post_id);
                }

                return $response;
            }
        }

        return [
            'status' => 0,
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
                    'post_type' => GMZ_SERVICE_TOUR
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