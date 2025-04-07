<?php

namespace App\Services;

use App\Jobs\SendOrderJob;
use App\Jobs\SendUserJob;
use App\Repositories\AgentAvailabilityRepository;
use App\Repositories\ApartmentAvailabilityRepository;
use App\Repositories\ApartmentRepository;
use App\Repositories\BeautyAvailabilityRepository;
use App\Repositories\BeautyRepository;
use App\Repositories\CarAvailabilityRepository;
use App\Repositories\CarRepository;
use App\Repositories\HotelRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RoleUserRepository;
use App\Repositories\RoomAvailabilityRepository;
use App\Repositories\RoomRepository;
use App\Repositories\SpaceAvailabilityRepository;
use App\Repositories\SpaceRepository;
use App\Repositories\TourAvailabilityRepository;
use App\Repositories\TourRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use TorMorten\Eventy\Facades\Eventy;

class OrderService extends AbstractService
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
        $this->repository = OrderRepository::inst();
    }

    public function getOrderDetail(Request $request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'title' => __('Alert System'),
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);
        $order_id = isset($params['orderID']) ? $params['orderID'] : '';
        $order_hashing = isset($params['orderHashing']) ? $params['orderHashing'] : 'none';

        if (!gmz_compare_hashing($order_id, $order_hashing)) {
            return [
                'status' => 0,
                'title' => __('Alert System'),
                'message' => __('Data is invalid')
            ];
        }

        $order = $this->repository->find($order_id);

        return [
            'status' => 1,
            'html' => view('Backend::components.modal.order-content', ['data' => $order])->render()
        ];
    }

    public function getPostsPagination($number = 10, $where = [])
    {
        return $this->repository->paginate($number, $where);
    }

    public function checkingBeforeCheckout($serviceObject, $cart)
    {
        if ($cart['post_type'] == GMZ_SERVICE_CAR) {
            if ($serviceObject['quantity'] < $cart['cart_data']['number']) {
                return [
                    'status' => false,
                    'message' => __('This Car is not available')
                ];
            }
        } elseif ($cart['post_type'] == GMZ_SERVICE_BEAUTY) {
            $beautyService = BeautyService::inst();
            $slot_data = $beautyService->getSlotEmptyByDay($cart['post_id'], $cart['cart_data']['check_in']);
            $check_out = false;
            if (isset($slot_data['slot'])) {
                foreach ($slot_data['slot'] as $value) {
                    if ($value['start'] == $cart['cart_data']['check_in'] && in_array($cart['cart_data']['agent_id'], $value['agent'], true)) {
                        $check_out = $value['end'];
                        break;
                    }
                }
            }

            if (!$check_out) {
                return [
                    'status' => false,
                    'message' => __('This Beauty is not available')
                ];
            }
        }
        return false;
    }

    public function checkOut(Request $request)
    {
        $cart = \Cart::inst()->getCart();
        if (!empty($cart)) {
            $actionRespon = apply_filter('gmz_before_do_checkout', [], $cart);
            if(!empty($actionRespon)){
                return $actionRespon;
            }
            $post_type = $cart['post_type'];
            $serviceRepo = '\\App\\Repositories\\' . ucfirst($post_type) . 'Repository';
            $serviceObject = $serviceRepo::inst()->find($cart['post_id']);
            if ($serviceObject) {
                $cart_data = $cart['cart_data'];
                $checkingBefore = $this->checkingBeforeCheckout($serviceObject, $cart);
                if (!empty($checkingBefore)) {
                    return $checkingBefore;
                }

                if (in_array($post_type, [GMZ_SERVICE_APARTMENT, GMZ_SERVICE_TOUR, GMZ_SERVICE_SPACE, GMZ_SERVICE_CAR, GMZ_SERVICE_BEAUTY])) {
                    $serviceAvaiRepo = '\\App\\Repositories\\' . ucfirst($post_type) . 'AvailabilityRepository';
                    $check_avail = $serviceAvaiRepo::inst()->checkAvailability($cart['post_id'], $cart_data['check_in'], $cart_data['check_out']);
                    if (!$check_avail) {
                        return [
                            'status' => false,
                            'message' => __('This service is not available')
                        ];
                    }
                } elseif ($post_type == GMZ_SERVICE_HOTEL) {
                    $roomAvaiRepo = RoomAvailabilityRepository::inst();
                    $room_avails = [];
                    $rooms = $cart_data['rooms'];
                    if (!empty($rooms)) {
                        foreach ($rooms as $k => $v) {
                            if ($v['number'] > 0) {
                                $avail = $roomAvaiRepo->checkAvailabilityWithGuest($k, $cart_data['check_in'], $cart_data['check_out'], $v['number'], $cart_data['adult'], $cart_data['children']);
                                if ($avail->isEmpty()) {
                                    $room_avails[] = $k;
                                }
                            }
                        }
                    }

                    if (empty($room_avails)) {
                        return [
                            'status' => false,
                            'message' => __('This service is not available.')
                        ];
                    }
                }

                //Validate form checkout
                $valid = Validator::make($request->all(), [
                    'first_name' => ['required'],
                    'email' => ['required', 'email'],
                    'phone' => ['required'],
                    'address' => ['required'],
                ]);

                if ($valid->fails()) {
                    return [
                        'status' => 0,
                        'message' => $valid->errors()->first()
                    ];
                }

                $post_data = $request->all();

                $agree = $request->post('agree', '');
                if ($agree != 1) {
                    return [
                        'status' => 0,
                        'message' => __('Please agree with our Terms and Conditions')
                    ];
                }

                if (!is_user_login()) {
                    $userRepo = UserRepository::inst();
                    $user_exists = $userRepo->where(['email' => $post_data['email']], true);
                    if (!empty($user_exists)) {
                        return [
                            'status' => false,
                            'message' => __('Your email already exists. Please login with that email or use another email')
                        ];
                    }

                    $user_password = random_user_password(8);
                    $user_data = [
                        'first_name' => $post_data['first_name'],
                        'last_name' => $post_data['last_name'],
                        'email' => $post_data['email'],
                        'password' => Hash::make($user_password),
                        'password_origin' => $user_password,
                        'address' => $post_data['address']
                    ];

                    $user_id = $userRepo->create($user_data);

                    if ($user_id) {
                        $roleUserRepo = RoleUserRepository::inst();
                        $roleUserRepo->create([
                            'role_id' => 3,
                            'user_id' => $user_id,
                        ]);
                        $user_data['user_id'] = $user_id;

                        Auth::attempt([
                            'email' => $post_data['email'],
                            'password' => $user_password
                        ], true);

                        dispatch(new SendUserJob($user_data));
                    } else {
                        return [
                            'status' => false,
                            'message' => __('Have an error when creating new user. Please try again.')
                        ];
                    }
                } else {
                    $user_id = get_current_user_id();
                }

                $payment = $post_data['payment_method'];
                if (empty($payment)) {
                    $payment = 'bank_transfer';
                }

                $gateways_obj = \Gateway::inst()->getGateway($payment);

                $checkout_data = $cart;
                unset($checkout_data['post_object']);

                $token_code = ($payment == 'stripe') ? $post_data['stripeToken'] : '';
                $commission = (get_option('commission')) ? get_option('commission') : 0;

                $order_data = Eventy::filter('gmz_checkout_data', array(
                    'sku' => uniqid(),
                    'post_id' => $cart['post_id'],
                    'total' => $cart['total'],
                    'number' => isset($cart_data['number']) ? $cart_data['number'] : 1,
                    'buyer' => $user_id,
                    'owner' => $serviceObject['author'],
                    'payment_type' => $payment,
                    'checkout_data' => json_encode($checkout_data),
                    'token_code' => $token_code,
                    'currency' => json_encode(current_currency()),
                    'start_date' => $cart_data['check_in'],
                    'end_date' => $cart_data['check_out'],
                    'start_time' => isset($cart_data['start_time']) ? $cart_data['start_time'] : 0,
                    'end_time' => isset($cart_data['end_time']) ? $cart_data['end_time'] : 0,
                    'post_type' => $post_type,
                    'payment_status' => GMZ_PAYMENT_PENDING,
                    'status' => GMZ_STATUS_INCOMPLETE,
                    'first_name' => $post_data['first_name'],
                    'last_name' => $post_data['last_name'],
                    'email' => $post_data['email'],
                    'phone' => $post_data['phone'],
                    'address' => $post_data['address'],
                    'city' => $post_data['city'],
                    'country' => $post_data['country'],
                    'postcode' => $post_data['postcode'],
                    'note' => $post_data['note'],
                    'commission' => $commission
                ), $cart);

                //Check add new or update order
                $order_token = $request->post('order_token');
                if (!empty($order_token)) {
                    $order_require_updates = $this->repository->findOneBy(['order_token' => $order_token]);
                    if (!empty($order_require_updates) && ($order_require_updates['payment_status'] == 0)) {
                        $this->repository->updateByWhere(['order_token' => $order_token], [
                            'first_name' => $post_data['first_name'],
                            'last_name' => $post_data['last_name'],
                            'email' => $post_data['email'],
                            'phone' => $post_data['phone'],
                            'address' => $post_data['address'],
                            'city' => $post_data['city'],
                            'country' => $post_data['country'],
                            'postcode' => $post_data['postcode'],
                            'note' => $post_data['note'],
                            'payment_type' => $payment,
                            'payment_status' => GMZ_PAYMENT_PENDING,
                        ]);
                        $this->repository->appendChangeLog($order_require_updates['id'], 'system', 're-order');
                        $re_order = true;
                        $order_id = $order_require_updates['id'];
                    }
                }

                if (empty($re_order)) {
                    $order_id = $this->repository->create($order_data);

                    $this->repository->updateByWhere(['id' => $order_id], [
                        'order_token' => gmz_hashing($order_id),
                        'sku' => 668 + $order_id,
                    ]);
                }

                $after_payment = \BaseGateway::inst()->doCheckout($gateways_obj, $order_id);
                if (!isset($after_payment['order_id'])) {
                    $after_payment['order_id'] = $order_id;
                }
                if (isset($after_payment['status']) && $after_payment['status']) {
                    if ($post_type == GMZ_SERVICE_HOTEL) {
                        $roomAvaiRepo = RoomAvailabilityRepository::inst();
                        $roomAvaiRepo->updateBookedData($cart_data['check_in'], $cart_data['check_out'], $cart_data['rooms']);
                    } elseif ($post_type == GMZ_SERVICE_CAR) {
                        $carAvaiRepo = CarAvailabilityRepository::inst();
                        $carAvaiRepo->updateBookedData($cart_data['check_in'], $cart_data['check_out'], $cart_data['number'], $serviceObject);
                    } elseif ($post_type == GMZ_SERVICE_APARTMENT) {
                        $apartmentAvaiRepo = ApartmentAvailabilityRepository::inst();
                        $apartmentAvaiRepo->updateBookedData($cart_data['check_in'], $cart_data['check_out'], $serviceObject);
                    } elseif ($post_type == GMZ_SERVICE_SPACE) {
                        $spaceAvaiRepo = SpaceAvailabilityRepository::inst();
                        $spaceAvaiRepo->updateBookedData($cart_data['check_in'], $cart_data['check_out'], $serviceObject);
                    } elseif ($post_type == GMZ_SERVICE_TOUR) {
                        $tourAvaiRepo = TourAvailabilityRepository::inst();
                        $tourAvaiRepo->updateBookedData($cart_data['check_in'], $cart_data['check_out'], $serviceObject, $cart['adult'], $cart['children']);
                    } elseif ($post_type == GMZ_SERVICE_BEAUTY) {
                        $agentAvaiRepo = AgentAvailabilityRepository::inst();
                        $agentAvaiRepo->updateBookedData($cart_data['check_in'], $cart_data['check_out'], $cart_data['agent_id'], $order_id, 'booked');
                    }
                }

                return $after_payment;
            }
        }
        return [
            'status' => false,
            'message' => __('The order is invalid')
        ];
    }

    public function paymentChecking($order_token, $status)
    {

        $order = $this->repository->findOneBy(['order_token' => $order_token]);

        if (empty($order)) {
            return false;
        }

        $payment = $order['payment_type'];
        $gateways_obj = \Gateway::inst()->getGateway($payment);
        $message = NULL;

        if (empty($status)) {
            //Payment is canceled
            $this->repository->appendChangeLog($order['id'], 'system', 'Payment process is canceled');
            $message = 'Payment process is canceled';
        } else {
            if ($gateways_obj->checkCompleteIsRequired()) {
                $response = $gateways_obj->checkCompletePurchase($order['id'], $order['total']);

                //payment success
                if (!empty($response['payment_status'])) {
                    $this->updatePaymentSuccess($order['id'], $response['transaction_id']);
                    add_money_to_wallet($order['id']);
                    return [
                        'payment_status' => GMZ_PAYMENT_COMPLETED,
                        'order_token' => $order['order_token']
                    ];
                } else {
                    $this->repository->appendChangeLog($order['id'], 'system', $response['message']);
                    $message = $response['message'];
                }
            } else {
                $this->updatePaymentSuccess($order['id'], $order['sku']);
                add_money_to_wallet($order['id']);
                return [
                    'payment_status' => GMZ_PAYMENT_COMPLETED,
                    'order_token' => $order['order_token']
                ];
            }
        }

        // Case 2: Payment does not require confirmation
        return [
            'payment_status' => $order['payment_status'],
            'order_token' => $order['order_token'],
            'message' => $message
        ];
    }

    public function updatePaymentSuccess($order_id, $transaction_id)
    {
        //remove cart
        \Cart::inst()->removeCart();
        //update payment status of order
        $this->repository->update($order_id, [
            'payment_status' => GMZ_PAYMENT_COMPLETED,
            'status' => GMZ_STATUS_COMPLETE,
            'transaction_id' => $transaction_id
        ]);
        $this->repository->appendChangeLog($order_id, 'system', 'payment success');
    }

    public function completeOrderChecking($request)
    {
        $order_token = $request->post('order_token');
        $order = NULL;

        if ($order_token) {
            $order = $this->repository->where(['order_token' => $order_token], true);
        } else if ($request->post('order_id')) {
            $order_id = $request->post('order_token');
            $order = $this->repository->where(['id' => $order_id], true);
        }

        if (!$order) {
            $notices = 'not_found';
        } elseif ($order['payment_status'] == 1) {
            $notices = 'payment_success';
        } else {
            $notices = 'payment_incomplete';
        }

        if ($order) {
            $change_log = $order['change_log'];
            if (strpos($change_log, 'Email sent!') === false) {
                try {
                    dispatch(new SendOrderJob($order));
                }catch (\Exception $e){}
                $this->repository->appendChangeLog($order['id'], 'System', 'Email sent!');
            }
        }

        return [
            'notices' => $notices,
            'order' => $order,
        ];
    }

    public function unsuccessfulPaymentProcessing($order_token)
    {
        $order = $this->repository->where(['order_token' => $order_token], true);
        $current_cart = \cart::inst()->getCart();

        if (empty($order) || ($order['payment_status'] == 1 || empty($current_cart))) {
            return NULL;
        }

        unset($current_cart['post_object']);
        $current_cart = json_encode($current_cart);

        if ($current_cart !== $order['checkout_data']) {
            return NULL;
        }

        return [
            'order_token' => $order['order_token'],
            'first_name' => $order['first_name'],
            'last_name' => $order['last_name'],
            'email' => $order['email'],
            'phone' => $order['phone'],
            'address' => $order['address'],
            'city' => $order['city'],
            'country' => $order['country'],
            'postcode' => $order['postcode'],
            'note' => $order['note'],
        ];
    }

    public function updateStatusOrder(Request $request)
    {
        $status_order = $request->post('status');
        $param = $request->post('params');
        $param = base64_decode($param);
        $param = json_decode($param, true);

        if (empty($param) || !is_order_status($status_order) || !is_user_login() || is_partner()) {
            return ['message' => 'Error! You are not allowed.', 'status' => 0];
        }

        $current_user_id = get_current_user_id();
        $order_id = $param['orderID'];

        if (is_customer() && ($status_order === GMZ_STATUS_CANCELLED)) {
            $order = $this->repository->where(['id' => $order_id, 'buyer' => $current_user_id], true);
        } elseif (is_admin() && is_order_status($status_order)) {
            $order = $this->repository->where(['id' => $order_id], true);
        }

        \Gateway::inst();

        if (!empty($order) && ($status_order !== $order['status'])) {

            switch ($status_order) {
                case GMZ_STATUS_CANCELLED:
                    if ($order['status'] == GMZ_STATUS_COMPLETE || $order['status'] == GMZ_STATUS_INCOMPLETE) {
                        $checkCancel = $this->checkPaymentCancellation($order);
                        if (is_admin() || $checkCancel == true) {
                            $update = ['status' => $status_order];
                        } else {
                            $message = __('The time limit for you to cancel your order has expired');
                            $update = false;
                        }
                    }
                    break;
                case GMZ_STATUS_REFUNDED:
                    if ((($order['status'] == GMZ_STATUS_COMPLETE) || ($order['status'] == GMZ_STATUS_CANCELLED)) && ($order['payment_status'] == GMZ_PAYMENT_COMPLETED)) {
                        $update = ['status' => $status_order];
                        subtract_money_form_wallet($order_id);
                    }
                    break;
                case GMZ_STATUS_COMPLETE:
                    if ($order['payment_status'] == GMZ_PAYMENT_PENDING || $order['status'] == GMZ_STATUS_REFUNDED) {
                        add_money_to_wallet($order_id);
                    }
                    $update = ['status' => $status_order, 'payment_status' => GMZ_PAYMENT_COMPLETED];
                    break;
                case GMZ_STATUS_INCOMPLETE:
                    if ($order['status'] == GMZ_STATUS_COMPLETE) {
                        $update = ['status' => $status_order, 'payment_status' => GMZ_PAYMENT_PENDING];
                        subtract_money_form_wallet($order_id);
                    }
                    break;
            }

            if (!empty($update)) {
                $response = $this->repository->update($order_id, $update);
                //update earning
                $this->repository->appendChangeLog($order_id, $current_user_id, 'change status to ' . $status_order);
            }

            //BEAUTY SERVICE: delete record in agent availability table if order is canceled
            if ($order['post_type'] == GMZ_SERVICE_BEAUTY && !empty($response)) {
                $agentAvaiRepo = AgentAvailabilityRepository::inst();
                if ($status_order == GMZ_STATUS_CANCELLED || $status_order == GMZ_STATUS_REFUNDED) {
                    //unbooked
                    $agentAvaiRepo->deleteByWhere(['order_id' => $order_id]);
                } else if ($status_order == GMZ_STATUS_COMPLETE || $status_order == GMZ_STATUS_INCOMPLETE) {
                    //booked
                    $checkout_data = json_decode($order['checkout_data'], true);
                    $cart_data = $checkout_data['cart_data'];
                    $agentAvaiRepo->updateBookedData($order['start_date'], $order['end_date'], $cart_data['agent_id'], $order_id, 'booked');
                }
            }


            if (empty($response) && empty($message)) {
                $message = __('Fail! You cannot do this');
            } elseif (!empty($response)) {
                $message = __('Success!');
            }

            return [
                'status' => (empty($response)) ? 0 : 1,
                'message' => $message,
                'statusHtml' => the_order_status($status_order),
                'listStatus' => list_order_status($status_order)
            ];
        }

        //Exception
        return ['message' => 'Error! Cannot change status.', 'status' => 0];

    }

    public function checkPaymentCancellation($order)
    {
        $post_id = $order["post_id"];
        $post_type = $order["post_type"];
        $message = false;
        if ($post_type == GMZ_SERVICE_CAR) {
            $carRepo = CarRepository::inst();
            $p = $carRepo->find($post_id);
        } elseif ($post_type == GMZ_SERVICE_APARTMENT) {
            $apartmentRepo = ApartmentRepository::inst();
            $p = $apartmentRepo->find($post_id);
        } elseif ($post_type == GMZ_SERVICE_TOUR) {
            $tourRepo = TourRepository::inst();
            $p = $tourRepo->find($post_id);
        } elseif ($post_type == GMZ_SERVICE_SPACE) {
            $spaceRepo = SpaceRepository::inst();
            $p = $spaceRepo->find($post_id);
        } elseif ($post_type == GMZ_SERVICE_HOTEL) {
            $hotelRepo = HotelRepository::inst();
            $p = $hotelRepo->find($post_id);
        }

        if (!empty($p['enable_cancellation']) && $p['enable_cancellation'] == 'on') {
            $orderEndTime = $order['end_time'];
            $cancelBefore = empty($p['cancel_before']) ? 0 : $p['cancel_before'];
            $str = '-' . $cancelBefore . ' day';
            $endTime = $orderEndTime - strtotime($str);
            if (TIME() < $endTime) {
                $message = true;
            } else {
                $message = false;
            }
        }
        return $message;
    }

    public function getOrderManagement($post_type, Request $request, $my_order = false)
    {
        //filter user
        $current_user = get_current_user_id();
        $where_raw = null;

        if ($my_order == true) {
            $where_raw = "buyer = '{$current_user}'";
        } else {
            //sales
            if (is_partner()) {
                $where_raw = "owner = '{$current_user}'";
            } elseif (is_admin()) {
                //default not query user
                //if has request user buyer
                $request_buyer = $request->get('buyer');
                $request_owner = $request->get('owner');
                if ($request_buyer && $request_owner) {
                    $where_raw = "owner = '{$request_owner}' AND buyer = '{$request_buyer}'";
                } elseif ($request_owner) {
                    $where_raw = "owner = '{$request_owner}'";
                } elseif ($request_buyer) {
                    $where_raw = "buyer = '{$request_buyer}'";
                }
            } else {
                //is_customer
                $where_raw = "buyer = '{$current_user}'";
            }
        }

        //filter post type
        switch ($post_type) {
            case "all":
                $where_raw .= ((empty($where_raw)) ? "1=1" : "");
                $view_name = 'all';
                break;
            default:
                $where_raw .= ((empty($where_raw)) ? "" : " AND ");
                $where_raw .= "post_type = '{$post_type}'";
                $view_name = $post_type;
                break;
        }

        //search
        $search = $request->get('search');
        if (!empty($search) && (is_partner() || is_admin())) {
            if (is_partner()) {
                $all_posts = $this->repository->searchPaginate(15, $search, $current_user);
            } else {
                $all_posts = $this->repository->searchPaginate(15, $search);
            }
            return [
                'all_posts' => $all_posts,
                'view_name' => $view_name
            ];
        }

        //filter by status
        $filter_status = $request->get('filter_status');
        $complete = GMZ_STATUS_COMPLETE;
        $cancelled = GMZ_STATUS_CANCELLED;
        $refunded = GMZ_STATUS_REFUNDED;
        $incomplete = GMZ_STATUS_INCOMPLETE;
        $payment_completed = GMZ_PAYMENT_COMPLETED;
        $payment_pending = GMZ_PAYMENT_PENDING;
        $current_time = TIME();

        switch ($filter_status) {
            case 'refund_request':
                $where_raw .= " AND status = '{$cancelled}' AND payment_status = '{$payment_completed}'";
                break;
            case
            'payment_confirmation':
                $where_raw .= " AND status = '{$incomplete}' AND payment_type = 'bank_transfer' AND payment_status = '{$payment_pending}' AND start_date > '{$current_time}'";
                break;
            case 'unfinished':
                $where_raw .= " AND status = '{$complete}' AND end_date > '{$current_time}'";
                break;
            case GMZ_STATUS_COMPLETE:
                $where_raw .= " AND status = '{$complete}'";
                break;
            case GMZ_STATUS_CANCELLED:
                $where_raw .= " AND status = '{$cancelled}'";
                break;
            case GMZ_STATUS_REFUNDED:
                $where_raw .= " AND status = '{$refunded}'";
                break;
            case GMZ_STATUS_INCOMPLETE:
                $where_raw .= " AND status = '{$incomplete}'";
                break;
        }

        //filter by OrderBy
        $order_by = $request->get('orderby');
        $order = $request->get('order');
        if (!in_array($order_by, ['updated_at', 'id', 'start_date', 'status'], true)) {
            $order_by = 'created_at';//default
        }
        if (!in_array($order, ['ASC', 'DESC'], true)) {
            $order = 'DESC';//default
        }
        $all_posts = $this->repository->getOrderPaginate(15, $where_raw, $order_by, $order);

        return [
            'all_posts' => $all_posts,
            'view_name' => $view_name
        ];
    }
}