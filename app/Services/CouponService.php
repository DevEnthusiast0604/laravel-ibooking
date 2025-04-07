<?php

namespace App\Services;

use App\Repositories\CouponRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use TorMorten\Eventy\Facades\Eventy;

class CouponService extends AbstractService
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
        $this->repository = CouponRepository::inst();
    }

    public function removeCoupon(Request $request)
    {
        return $this->calcCoupon('remove');
    }

    public function applyCoupon(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'coupon' => ['required']
        ]);

        if ($valid->fails()) {
            return [
                'status' => 0,
                'message' => $valid->errors()->first()
            ];
        }

        $coupon_code = $request->post('coupon');
        $exists = $this->repository->checkCouponExists($coupon_code, strtotime(date('Y-m-d')));
        if (is_null($exists)) {
            return [
                'status' => 0,
                'message' => __('This coupon is invalid')
            ];
        }

        $check = Eventy::filter('gmz_apply_coupon', [], $coupon_code, $exists);
        if (!empty($check)) {
            return $check;
        }

        $coupon_code = $exists['code'];
        $coupon_percent = $exists['percent'];

        return $this->calcCoupon('apply', $coupon_code, $coupon_percent);
    }

    public function calcCoupon($action, $code = '', $percent = '')
    {
        $cart = \Cart::inst()->getCart();
        $base_price = $cart['base_price'];
        $equipment_price = isset($cart['equipment_price']) ? $cart['equipment_price'] : 0;
        $insurance_price = isset($cart['insurance_price']) ? $cart['insurance_price'] : 0;
        $sub_total = $base_price + $equipment_price + $insurance_price;
        if ($action == 'apply') {
            $discount = ($sub_total * $percent) / 100;
            $sub_total = $sub_total - $discount;
            $cart['coupon'] = $code;
            $cart['coupon_value'] = $discount;
            $cart['coupon_percent'] = $percent;
            $cart['sub_total'] = $sub_total;

            $cart['cart_data']['coupon_data'] = [
                'code' => $code,
                'percent' => $percent,
                'value' => $discount
            ];
        } else {
            $cart['coupon'] = '';
            $cart['coupon_value'] = 0;
            $cart['coupon_percent'] = 0;
            $cart['sub_total'] = $sub_total;

            $cart['cart_data']['coupon_data'] = [];
        }

        $total = $sub_total;

        $tax = $cart['tax'];
        if ($tax['included'] == 'off') {
            $total += ($total * $tax['percent'] / 100);
        }

        $cart['total'] = $total;

        \Cart::inst()->setCart($cart);

        if ($action == 'apply') {
            return [
                'status' => true,
                'message' => __('Apply coupon successfully'),
                'reload' => true
            ];
        } else {
            return [
                'status' => true,
                'message' => __('Remove coupon successfully'),
                'reload' => true
            ];
        }
    }

    public function changeStatus(Request $request)
    {
        $params = json_decode(base64_decode($request->post('params', [])), true);
        $status = $request->post('approve', 'on');

        $coupon_id = isset($params['couponID']) ? $params['couponID'] : '';
        $coupon_hashing = isset($params['couponHashing']) ? $params['couponHashing'] : '';

        if (!gmz_compare_hashing($coupon_id, $coupon_hashing)) {
            return [
                'status' => 0,
                'title' => __(__('System Alert')),
                'message' => __('This coupon is invalid')
            ];
        }

        $data = [
            'status' => $status == 'yes' ? 'publish' : 'pending'
        ];

        $updated = $this->repository->update($coupon_id, $data);

        if ($updated) {
            return [
                'status' => 1,
                'title' => __(__('System Alert')),
                'message' => __('Updated Successfully'),
            ];
        } else {
            return [
                'status' => 0,
                'title' => __(__('System Alert')),
                'message' => __('Can not update this coupon')
            ];
        }
    }

    public function deleteCoupon($request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);
        $coupon_id = isset($params['couponID']) ? $params['couponID'] : '';
        $coupon_hashing = isset($params['couponHashing']) ? $params['couponHashing'] : 'none';

        if (!gmz_compare_hashing($coupon_id, $coupon_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $this->repository->delete($coupon_id);

        return [
            'status' => 1,
            'message' => __('Delete coupon successfully'),
            'reload' => true
        ];
    }

    public function editCoupon($request)
    {
        $post_data = $request->all();
        $post_data = $this->mergeData($post_data, $post_data['fields']);

        $coupon_id = $post_data['coupon_id'];

        $existing = $this->repository->checkExistsByTitle($coupon_id, $post_data['code']);
        if ($existing) {
            return [
                'status' => 0,
                'message' => __('This coupon already exists.')
            ];
        }

        if ($post_data['percent'] < 0 || $post_data['percent'] > 100) {
            return [
                'status' => 0,
                'message' => __('The discount value is invalid.')
            ];
        }

        $start_date = strtotime($post_data['start_date']);
        $end_date = strtotime($post_data['end_date']);
        $post_data['start_date'] = $start_date;
        $post_data['end_date'] = $end_date;
        if ($start_date > $end_date) {
            return [
                'status' => 0,
                'message' => __('Start date need to less than end date.')
            ];
        }

        $updated = $this->repository->update($coupon_id, $post_data);

        if ($updated) {
            return [
                'status' => 1,
                'message' => __('Update term successfully'),
                'reload' => true
            ];
        }

        return [
            'status' => 0,
            'message' => __('Update term failed')
        ];
    }

    public function getCouponForm($request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);
        $coupon_id = isset($params['couponID']) ? $params['couponID'] : '';
        $coupon_hashing = isset($params['couponHashing']) ? $params['couponHashing'] : 'none';

        if (!gmz_compare_hashing($coupon_id, $coupon_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $data = [
            'action' => dashboard_url('new-coupon'),
            'title' => __('Add New'),
            'coupon_id' => $coupon_id,
            'coupon_object' => []
        ];
        if (!empty($coupon_id)) {
            $coupon_object = $this->repository->find($coupon_id);
            $data['action'] = dashboard_url('edit-coupon');
            $data['title'] = __('Edit Coupon');
            $data['coupon_object'] = $coupon_object;
            $coupon_object->start_date = date('Y-m-d', $coupon_object->start_date);
            $coupon_object->end_date = date('Y-m-d', $coupon_object->end_date);
        }

        return [
            'status' => 1,
            'html' => view('Backend::components.modal.coupon-content', ['data' => $data])->render()
        ];
    }

    public function newCoupon($request)
    {
        $post_data = $request->all();
        $coupon_code = $post_data['code'];

        $post_data = $this->mergeData($post_data, $post_data['fields']);

        if (isset($post_data['percent'])) {
            $post_data['percent'] = floatval($post_data['percent']);
        }

        if (isset($post_data['code'])) {
            $post_data['code'] = esc_html($post_data['code']);
        }

        $existing = $this->repository->findOneBy(['code' => $coupon_code]);

        if ($existing) {
            return [
                'status' => 0,
                'message' => __('This coupon already exists.')
            ];
        }

        if ($post_data['percent'] < 0 || $post_data['percent'] > 100) {
            return [
                'status' => 0,
                'message' => __('The discount value is invalid.')
            ];
        }

        $start_date = strtotime($post_data['start_date']);
        $end_date = strtotime($post_data['end_date']);
        $post_data['author'] = get_current_user_id();
        $post_data['status'] = 'publish';
        $post_data['start_date'] = $start_date;
        $post_data['end_date'] = $end_date;
        if ($start_date > $end_date) {
            return [
                'status' => 0,
                'message' => __('Start date need to less than end date.')
            ];
        }

        $inserted = $this->repository->save($post_data);

        if ($inserted) {
            return [
                'status' => 1,
                'message' => __('Add new coupon successfully'),
                'reload' => true
            ];
        }

        return [
            'status' => 0,
            'message' => __('Add new coupon failed')
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
}