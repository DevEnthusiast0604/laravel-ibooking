<?php

namespace App\Services;

use App\Repositories\EarningsRepository;
use App\Repositories\OrderRepository;
use App\Repositories\WithdrawalRepository;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class EarningsReportService extends AbstractService
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
        $this->repository = EarningsRepository::inst();
    }

    public function getEarningsData()
    {
        return $this->repository->paginate(20);
    }

    public function getWidget(Request $request)
    {
        $widget = $request->post('widget');
        $userID = $request->post('userID');
        if (is_customer() || (is_partner() && ($userID != get_current_user_id()))) {
            return false;
        }

        if (method_exists($this, $widget) && strstr($widget, "widget")) {
            $data = $this->$widget($request, $userID);
            return [
                'widget' => $widget,
                'data' => $data
            ];
        }
        return false;
    }

    public function widgetTransactions($request, $userID)
    {
        $orderRepo = OrderRepository::inst();
        return $orderRepo->getRecentDeals($userID, 10);
    }

    public function widgetIncomeStatistics(Request $request, $userID)
    {

        $dt = Carbon::now();
        $startDate = $dt->today()->subDays(6)->toDateString();
        $endDate = $dt->today()->toDateString();
        //get list period
        $period = CarbonPeriod::create($startDate, $endDate);
        //format date
        $dates = array();
        foreach ($period as $date) {
            $dates[] = $date->toDateString();
        }

        //get chart data
        $orderRepo = OrderRepository::inst();
        $results = $orderRepo->getRevenue($userID, $startDate, $endDate);
        $data_total = $data_net_earnings = array_fill(0, count($dates), 0);
        foreach ($results as $value) {
            if (false !== ($key = array_search($value['order_date'], $dates))) {
                $data_total[$key] = convert_price($value['sum_total'], false);
                $data_net_earnings[$key] = convert_price($value['order_total'], false);
            }
        }

        //get wallet
        if ($userID == -1) {
            $wallet = $this->repository->totalEarnings();
            $wallet = [
                "total" => $wallet['total_earnings'],
                "balance" => $wallet['total_balance'],
                "net_earnings" => $wallet['total_net_earnings'],
            ];
        } else {
            $wallet = $this->repository->findOneBy(["user_id" => $userID]);
            $wallet = $wallet->getAttributes();
        }


        return [
            'wallet' => $wallet,
            'data_total' => $data_total,
            'data_net_earnings' => $data_net_earnings
        ];
    }

    public function widgetRevenue(Request $request, $userID)
    {
        $dt = Carbon::now();
        $startDate = empty($request->post('startDate')) ? $dt->subDays(29)->toDateString() : $request->post('startDate');
        $endDate = empty($request->post('endDate')) ? $dt->today()->toDateString() : $request->post('endDate');

        $orderRepo = OrderRepository::inst();
        $results = $orderRepo->getRevenue($userID, $startDate, $endDate);
        //get list period
        $period = CarbonPeriod::create($startDate, $endDate);
        $dates = $labels = array();

        //format date
        foreach ($period as $date) {
            $dates[] = $date->toDateString();
            $labels[] = date(get_date_format(), $date->timestamp);
        }

        $description = $data = array_fill(0, count($dates), 0);
        $total = 0;

        foreach ($results as $value) {
            $money = convert_price($value['order_total'], false);
            if (false !== ($key = array_search($value['order_date'], $dates))) {
                $data[$key] = $money;
                $description[$key] = $value['order_count'];
            }
            $total += $value['order_total'];
        }

        $data_chart = [
            'series' => [
                array(
                    'name' => 'car',
                    'data' => $data,
                    'description' => $description
                )
            ],
            'labels' => $labels,
            'total' => convert_price($total)
        ];

        $menu = [
            array(
                'name' => 'Last 30 days',
                'start' => $dt->today()->subDays(29)->toDateString(),
                'end' => $dt->today()->toDateString()
            ),
            //current month
            array(
                'name' => $dt->today()->format('M Y'),
                'start' => $dt->today()->startOfMonth()->toDateString(),
                'end' => $dt->today()->toDateString()
            ),
            //last month
            array(
                'name' => $dt->today()->subMonth()->format('M Y'),
                'start' => $dt->today()->subMonth()->startOfMonth()->toDateString(),
                'end' => $dt->today()->subMonth()->lastOfMonth()->toDateString()
            ),
        ];
        $min_date = $orderRepo->getMinDate($userID);
        $date_range = [
            'min' => (isset($min_date['min_date'])) ? $min_date['min_date'] : "2021/01/01",
            'max' => $dt->today()->toDateString(),
            'default' => $dt->today()->subDays(29)->toDateString()
        ];

        return [
            'data' => $data_chart,
            'menu' => $menu,
            'range' => $date_range
        ];

    }

    public function widgetBalance(Request $request, $userID)
    {
        //get wallet
        if ($userID == -1) {
            $wallet = $this->repository->totalEarnings();
            $wallet = [
                "total" => $wallet['total_earnings'],
                "balance" => $wallet['total_balance'],
                "net_earnings" => $wallet['total_net_earnings'],
            ];
        } else {
            $wallet = $this->repository->where(['user_id' => $userID], true);
            $wallet = $wallet->getAttributes();
        }
        $wallet['on_hold'] = get_money_on_hold($userID);
        return $wallet;
    }

    public function widgetTotalOrders(Request $request, $userID)
    {
        if (!is_admin() && !is_partner()) {
            return false;
        }

        if (is_admin()) {
            $userID = '';
        }
        $orderRepo = OrderRepository::inst();
        $totalOrder = $orderRepo->totalOrders($userID);
        $statistics = $orderRepo->getStatisticsPerDay(7, $userID);

        $dates = get_list_date_form_today(6);

        $total_order_for_day = array_fill(0, count($dates), 0);

        foreach ($statistics as $value) {
            if (false !== ($key = array_search($value['order_date'], $dates))) {
                $total_order_for_day[$key] = $value['order_count'];
            }
        }

        return [
            'total' => $totalOrder,
            'data_chart' => $total_order_for_day
        ];
    }

    public function widgetTotalEarnings(Request $request, $userID)
    {
        if (!is_admin() && !is_partner()) {
            return false;
        }

        if (is_admin()) {
            $userID = '';
        }
        $orderRepo = OrderRepository::inst();
        $total_earnings = $this->repository->totalEarnings($userID);
        $statistics = $orderRepo->getStatisticsPerDay(7, $userID);
        $dates = get_list_date_form_today(6);

        $total_earnings_per_day = array_fill(0, count($dates), 0);

        foreach ($statistics as $value) {
            if (false !== ($key = array_search($value['order_date'], $dates))) {
                $total_earnings_per_day[$key] = convert_price($value['sum_total'], false);
            }
        }

        return [
            'total' => $total_earnings['total_earnings'],
            'data_chart' => $total_earnings_per_day
        ];
    }

    public function widgetNetEarnings(Request $request, $userID)
    {
        if (!is_admin() && !is_partner()) {
            return false;
        }

        if (is_admin()) {
            $userID = '';
        }
        $orderRepo = OrderRepository::inst();
        $total_earnings = $this->repository->totalEarnings($userID);
        $statistics = $orderRepo->getStatisticsPerDay(7, $userID);
        $dates = get_list_date_form_today(6);

        $total_earnings_per_day = array_fill(0, count($dates), 0);

        foreach ($statistics as $value) {
            if (false !== ($key = array_search($value['order_date'], $dates))) {
                $total_earnings_per_day[$key] = convert_price($value['net_earn'], false);
            }
        }

        return [
            'total' => $total_earnings['total_net_earnings'],
            'data_chart' => $total_earnings_per_day
        ];
    }

    public function widgetTotalCommission(Request $request, $userID)
    {
        if (!is_admin() && !is_partner()) {
            return false;
        }

        if (is_admin()) {
            $userID = '';
        }
        $orderRepo = OrderRepository::inst();
        $total_earnings = $this->repository->totalEarnings($userID);
        $statistics = $orderRepo->getStatisticsPerDay(7, $userID);
        $dates = get_list_date_form_today(6);

        $total_earnings_per_day = array_fill(0, count($dates), 0);
        foreach ($statistics as $value) {
            if (false !== ($key = array_search($value['order_date'], $dates))) {
                $commission = $value['sum_total'] - $value['net_earn'];
                $total_earnings_per_day[$key] = convert_price($commission, false);
            }
        }

        return [
            'total' => $total_earnings['total_commission'],
            'data_chart' => $total_earnings_per_day
        ];
    }

    public function widgetPendingTask(Request $request, $userID)
    {
        if (!is_admin() && !is_partner()) {
            return false;
        }

        if (is_admin()) {
            $userID = '';
        }
        $orderRepo = OrderRepository::inst();
        $withdrawalRepo = WithdrawalRepository::inst();
        $task_order = $orderRepo->getPendingOrders($userID);
        $task_withdrawal = $withdrawalRepo->countByWhere(["status" => GMZ_STATUS_PENDING]);

        $view_refund_request = dashboard_url('order/all?filter_status=refund_request');
        $payment_confirmation = dashboard_url('order/all?filter_status=payment_confirmation');

        $withdrawal_pending = $task_refund = $task_confirm = 0;
        foreach ($task_order as $value) {
            switch ($value['status']) {
                case GMZ_STATUS_COMPLETE:
                    $task_refund = $value['tasks'];
                    break;
                case GMZ_STATUS_INCOMPLETE:
                    $task_confirm = $value['tasks'];
                    break;
            }
        }

        if (!empty($task_withdrawal['count'])) {
            $withdrawal_pending = $task_withdrawal['count'];
        }
        $total_task = $task_refund + $task_confirm + $withdrawal_pending;

        return [
            'task' => [
                'order_refund' => $task_refund,
                'order_confirm' => $task_confirm,
                'withdrawal_pending' => $withdrawal_pending,
                'total' => $total_task
            ],
            'link' => [
                'refund' => $view_refund_request,
                'confirm' => $payment_confirmation,
                'withdrawal' => dashboard_url('withdrawal')
            ],
        ];

    }


}