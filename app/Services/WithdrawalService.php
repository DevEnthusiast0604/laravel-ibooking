<?php

namespace App\Services;

use App\Repositories\EarningsRepository;
use App\Repositories\UserRepository;
use App\Repositories\WithdrawalRepository;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Self_;

class WithdrawalService extends AbstractService
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
        $this->repository = WithdrawalRepository::inst();
    }

    public function getWithDrawalData($id = null)
    {
        if (is_admin()) {
            if (empty($id)) {
               $id = get_current_user_id();
            }
            $response = $this->repository->paginate(10, ['user_id' => $id]);
        } elseif (is_partner()) {
            $id = get_current_user_id();
            $response = $this->repository->paginate(10, ['user_id' => $id]);
        } else {
            abort(403);
        }
        return $response;
    }

    public function getWallet($id = null)
    {
        if (empty($id)) {
            $id = get_current_user_id();
        }

        $earningsRepository = EarningsRepository::inst();
        $data = $earningsRepository->findOneBy(["user_id" => $id]);

        if (empty($data)) {
            abort('403', 'Can\'t find your wallet');
        }
        $on_hold = get_money_on_hold($id);
        $data['on_hold'] = $on_hold;
        $data['max_withdrawal'] = $data['balance'] - $on_hold;

        return $data;

    }

    public function withdrawalRequest(Request $request)
    {
        $withdrawal = $request->post('withdrawal');
        $user_id = get_current_user_id();
        $wallet = $this->getWallet();
        $max_withdrawal = $wallet['max_withdrawal'];
        //get total withdrawal pending
        $withdrawal_pending = $this->repository->sumByWhere('withdraw', ["user_id" => $user_id, "status" => GMZ_STATUS_PENDING]);

        $max_withdrawal = round($max_withdrawal, 2);
        $withdrawal = round($withdrawal, 2);
        $withdrawal_pending = round($withdrawal_pending['sum'], 2);

        if ($max_withdrawal < ($withdrawal_pending + $withdrawal)) {
            return [
                'status' => 'warning',
                'message' => __('The total amount you requested to withdraw is already greater than the amount you can withdraw.'),
            ];
        } elseif (!empty($withdrawal) && ($max_withdrawal >= $withdrawal) && !is_customer()) {
            $this->repository->create([
                'user_id' => get_current_user_id(),
                'withdraw' => $withdrawal,
                'status' => GMZ_STATUS_PENDING
            ]);
            return [
                'status' => 'success',
                'message' => __('You have successfully requested your withdrawal.'),
            ];
        }

        return [
            'status' => 'warning',
            'message' => __('The amount you want to withdraw is incorrect'),
        ];
    }

    public function withdrawalUpdateStatus(Request $request)
    {
        if (!is_admin()) {
            return false;
        }

        $status_new = $request->post('status');
        $id = $request->post('params');
        //get withdrawal request
        $wrq = $this->repository->findOrFail($id);

        if (is_withdrawal_status($status_new) && $wrq['status'] != $status_new) {

            $user_id = $wrq['user_id'];
            //get wallet
            $wallet = $this->getWallet($user_id);
            $earningsRepository = EarningsRepository::inst();
            if (($status_new == GMZ_STATUS_ACCEPT) && ($wallet['max_withdrawal'] >= $wrq['withdraw'])) {
                $balance = $wallet['balance'] - $wrq['withdraw'];
                $updateWallet = $earningsRepository->updateByWhere(['user_id' => $user_id], ['balance' => $balance]);
                if ($updateWallet) {
                    $this->repository->update($id, ['status' => $status_new]);
                };
            } elseif (($status_new == GMZ_STATUS_CANCELLED) && ($wrq['status'] == GMZ_STATUS_ACCEPT)) {
                $balance = $wallet['balance'] + $wrq['withdraw'];
                $updateWallet = $earningsRepository->updateByWhere(['user_id' => $user_id], ['balance' => $balance]);
                if ($updateWallet) {
                    $this->repository->update($id, ['status' => $status_new]);
                };
            } else {
                $this->repository->update($id, ['status' => $status_new]);
            }
            return [
                'status' => 1,
                'message' => 'Update success!',
                'statusHtml' => get_withdrawal_status($status_new),
            ];
        } else {
            return [
                'status' => 0,
                'message' => 'Error! do not allow.'
            ];
        }
    }

    public function getDataModal(Request $request)
    {
        if (!is_admin()) {
            return false;
        }
        $id = $request->get("id");
        $userRepo = UserRepository::inst();
        $user = $userRepo->findOrFail($id);
        if (!empty($user['payout'])) {
            return nl2br(esc_html($user['payout']));
        } else {
            return __('Payout account not available');
        }

    }
}