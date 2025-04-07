<?php

namespace App\Services;

use App\Jobs\SendPartnerApprovedJob;
use App\Jobs\SendPartnerRequestJob;
use App\Repositories\EarningsRepository;
use App\Repositories\RoleUserRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserService extends AbstractService
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
        $this->repository = UserRepository::inst();
    }

    public function getUserData($id)
    {
        return $this->repository->find($id);
    }

    public function partnerRegister(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'first_name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if ($valid->fails()) {
            return [
                'status' => 0,
                'message' => $valid->errors()->first()
            ];
        }

        $agree = $request->post('agree', '');

        if (empty($agree)) {
            return [
                'status' => 0,
                'message' => __('Please agree with our terms and conditions')
            ];
        }

        $post_data = $request->all();

        $existing = $this->repository->where([
            'email' => $post_data['email']
        ], true);

        if (!empty($existing)) {
            return [
                'status' => 0,
                'title' => __('Alert System'),
                'message' => __('User already exists.')
            ];
        }

        $post_data['password'] = Hash::make($post_data['password']);

        $post_data['request'] = 1;

        $post_data_insert = $post_data;
        unset($post_data_insert['agree']);
        $inserted = $this->repository->create($post_data_insert);

        $roleUserRepo = RoleUserRepository::inst();

        $roleUserRepo->create([
            'role_id' => 3,
            'user_id' => $inserted,
        ]);

        if ($inserted) {
            $admin_id = get_option('admin_user');
            \GMZ_Notification::inst()->addNew($admin_id, $admin_id, __('New Partner request'), __('New Partner request on ') . date(get_date_format()));
            dispatch(new SendPartnerRequestJob($post_data));
            return [
                'status' => 1,
                'message' => __('Send your request successfully. Please wait admin review your account. Now, you can login as normal user on site')
            ];
        }

        return [
            'status' => 0,
            'message' => __('Send your request failed')
        ];
    }

    public function approvePartner($request)
    {
        $params = $request->post('params', '');
        $approve = $request->post('approve', '');
        if (empty($params) || empty($approve)) {
            return [
                'status' => 0,
                'title' => __('Alert System'),
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);
        $user_id = isset($params['userID']) ? $params['userID'] : '';
        $user_hashing = isset($params['userHashing']) ? $params['userHashing'] : 'none';

        if (!gmz_compare_hashing($user_id, $user_hashing)) {
            return [
                'status' => 0,
                'title' => __('Alert System'),
                'message' => __('Data is invalid')
            ];
        }

        $roleUserRepo = RoleUserRepository::inst();

        if ($approve == 'yes') {
            $earningRepo = EarningsRepository::inst();
            $roleUserRepo->updateByWhere(['user_id' => $user_id],
                ['role_id' => 2]);
            $check_exists_earning = $earningRepo->where(['user_id' => $user_id], true);
            if (is_null($check_exists_earning) || empty($check_exists_earning)) {
                $earningRepo->create([
                    'user_id' => $user_id,
                    'total' => 0,
                    'balance' => 0,
                    'net_earnings' => 0
                ]);
            }
            \GMZ_Notification::inst()->addNew('', $user_id, __('Partner was approved'), __('Your partner account has been approved on ') . date(get_date_format()));
            dispatch(new SendPartnerApprovedJob($user_id));
        } else {
            $roleUserRepo->updateByWhere(['user_id' => $user_id],
                ['role_id' => 3]);
            $this->repository->updateByWhere(['id' => $user_id],
                ['request' => 1]);
        }

        return [
            'status' => 1,
            'title' => __('Alert System'),
            'message' => __('Update user successfully'),
            'reload' => true
        ];
    }

    public function upgradePartner($action)
    {
        if ($action == 'become-partner') {
            $this->repository->update(get_current_user_id(), [
                'request' => 1
            ]);
            return 1;
        } elseif ($action == 'cancel-become-partner') {
            $this->repository->update(get_current_user_id(), [
                'request' => 0
            ]);
            return 2;
        }
        return false;
    }

    public function getPartnerPagination($number = 10, $where = [])
    {
        return $this->repository->getListPartner($number);
    }

    public function deleteUser($request)
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
        $user_id = isset($params['userID']) ? $params['userID'] : '';
        $user_hashing = isset($params['userHashing']) ? $params['userHashing'] : 'none';

        if (!gmz_compare_hashing($user_id, $user_hashing)) {
            return [
                'status' => 0,
                'title' => __('Alert System'),
                'message' => __('Data is invalid')
            ];
        }

        $this->repository->delete($user_id);

        $roleUserRepo = RoleUserRepository::inst();
        $earningRepo = EarningsRepository::inst();

        $roleUserRepo->deleteByWhere([
            'user_id' => $user_id
        ]);
        $earningRepo->deleteByWhere([
            'user_id' => $user_id
        ]);

        return [
            'status' => 1,
            'title' => __('Alert System'),
            'message' => __('Delete user successfully'),
            'reload' => true
        ];
    }

    public function editUser($request)
    {
        $valid = Validator::make($request->all(), [
            'first_name' => ['required'],
            'email' => ['required', 'email']
        ]);

        if ($valid->fails()) {
            return [
                'status' => 0,
                'title' => __('Alert System'),
                'message' => $valid->errors()->first()
            ];
        }

        $post_data = $request->all();

        $user_id = $post_data['user_id'];
        $email = $post_data['email'];

        $current_user = $this->repository->find($user_id);
        $current_email = $current_user->email;

        if ($current_email !== $email) {
            $existing = $this->repository->where([
                'email' => $email
            ]);
            if (!empty($existing)) {
                return [
                    'status' => 0,
                    'title' => __('Alert System'),
                    'message' => __('This user already exists.')
                ];
            }
        }

        unset($post_data['user_id']);

        if (!empty($post_data['password']) && $post_data['password'] !== null) {
            $post_data['password'] = Hash::make($post_data['password']);
        } else {
            unset($post_data['password']);
        }

        if ($post_data['role'] == '2') {
            $post_data['request'] = 1;
        }

        if ($post_data['role'] == '3') {
            $post_data['request'] = 0;
        }

        $post_data_update = $post_data;
        unset($post_data_update['role']);
        $updated = $this->repository->update($user_id, $post_data_update);

        $roleUserRepo = RoleUserRepository::inst();
        $roleUserRepo->updateByWhere(['user_id' => $user_id],
            ['role_id' => $post_data['role']]);

        if ($updated) {
            return [
                'status' => 1,
                'title' => __('Alert System'),
                'message' => __('Update user successfully'),
                'reload' => true
            ];
        }

        return [
            'status' => 0,
            'title' => __('Alert System'),
            'message' => __('Update user failed')
        ];
    }

    public function newUser($request)
    {
        $valid = Validator::make($request->all(), [
            'first_name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if ($valid->fails()) {
            return [
                'status' => 0,
                'title' => __('Alert System'),
                'message' => $valid->errors()->first()
            ];
        }

        $post_data = $request->all();

        $existing = $this->repository->where([
            'email' => $post_data['email']
        ], true);

        if (!empty($existing)) {
            return [
                'status' => 0,
                'title' => __('Alert System'),
                'message' => __('User already exists.')
            ];
        }

        $post_data['password'] = Hash::make($post_data['password']);

        if ($post_data['role'] == '2') {
            $post_data['request'] = 1;
        }

        if ($post_data['role'] == '3') {
            $post_data['request'] = 0;
        }

        $post_data_insert = $post_data;
        unset($post_data_insert['user_id']);
        unset($post_data_insert['role']);

        $inserted = $this->repository->create($post_data_insert);

        if ($inserted) {
            $roleUserRepo = RoleUserRepository::inst();
            $roleUserRepo->create([
                'role_id' => $post_data['role'],
                'user_id' => $inserted,
            ]);

            if ($post_data['role'] == '2') {
                $earningRepo = EarningsRepository::inst();
                $check_exists_earning = $earningRepo->where(['user_id' => $inserted], true);
                if (is_null($check_exists_earning) || empty($check_exists_earning)) {
                    $earningRepo->create([
                        'user_id' => $inserted,
                        'total' => 0,
                        'balance' => 0,
                        'net_earnings' => 0
                    ]);
                }
            }

            $post_data['user_id'] = $inserted;
            $post_data['password_origin'] = $post_data['password'];
            $admin_id = get_option('admin_user');
            \GMZ_Notification::inst()->addNew($admin_id, $admin_id, __('New user was created'), __('New User was create on ') . date(get_date_format()));
            return [
                'status' => 1,
                'title' => __('Alert System'),
                'message' => __('Add new user successfully'),
                'reload' => true
            ];
        }

        return [
            'status' => 0,
            'title' => __('Alert System'),
            'message' => __('Add new user failed')
        ];
    }

    public function getUserForm($request)
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
        $user_id = isset($params['userID']) ? $params['userID'] : '';
        $user_hashing = isset($params['userHashing']) ? $params['userHashing'] : 'none';

        if (!gmz_compare_hashing($user_id, $user_hashing)) {
            return [
                'status' => 0,
                'title' => __('Alert System'),
                'message' => __('Data is invalid')
            ];
        }

        $data = [
            'action' => dashboard_url('new-user'),
            'title' => __('Add New'),
            'user_id' => $user_id,
            'user_object' => []
        ];

        if (!empty($user_id)) {
            $user_object = $this->repository->find($user_id);
            $user_object->password = '';
            $data['action'] = dashboard_url('edit-user');
            $data['title'] = __('Edit User');
            $data['user_object'] = $user_object;
            $role = get_user_role($user_id, 'id');
            $user_object->role = $role;
        }

        return [
            'status' => 1,
            'html' => view('Backend::components.modal.user-content', ['data' => $data])->render()
        ];
    }

    public function getUsersPagination($number = 10, $where = [])
    {
        return $this->repository->paginate($number, $where, false);
    }

    public function updateProfile(Request $request)
    {
        $rules = [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'phone' => ['numeric']
        ];
        $password = $request->post('password', '');
        $phone = $request->post('phone', '');
        $confirm_password = $request->post('password_confirmation', '');

        if (!empty($password) || !empty($confirm_password)) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }
        $valid = Validator::make($request->all(), $rules);

        if ($valid->fails()) {
            return [
                'status' => false,
                'message' => view('Frontend::components.alert', ['type' => 'danger', 'message' => $valid->errors()->first()])->render()
            ];
        }

        if (strlen($phone) > 20) {
            return [
                'status' => false,
                'message' => view('Frontend::components.alert', ['type' => 'danger', 'message' => __('The phone may not be greater than 20.')])->render()
            ];
        }

        $data = apply_filter('gmz_user_insert_data', [
            'first_name' => $request->post('first_name', ''),
            'last_name' => $request->post('last_name', ''),
            'address' => $request->post('address', ''),
            'avatar' => $request->post('avatar', ''),
            'phone' => $request->post('phone', ''),
            'payout' => $request->post('payout', ''),
            'description' => $request->post('description', ''),
        ], $request->all());

        if (!empty($password) && !empty($confirm_password)) {
            $data['password'] = Hash::make($password);
        }

        $updated = $this->repository->update(get_current_user_id(), $data);
        if ($updated) {

            do_action('gmz_update_user_info', $request->all());

            return [
                'status' => false,
                'message' => view('Frontend::components.alert', ['type' => 'success', 'message' => __('Update successfully')])->render()
            ];
        }

        return [
            'status' => false,
            'message' => view('Frontend::components.alert', ['type' => 'danger', 'message' => __('Update failed')])->render()
        ];
    }
}