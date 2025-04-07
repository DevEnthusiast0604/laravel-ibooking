<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DashboardController extends Controller
{
    public function emailCheckerAction(Request $request)
    {
        $to = $request->post('email_to', '');
        if (empty($to)) {
            return response()->json([
                'status' => false,
                'message' => __('Please fill all the fields')
            ]);
        }

        $site_name = get_translate(get_option('site_name'));
        $subject = sprintf(__('%s - Checking Email'), $site_name);
        $content = 'Content email checking';
        $sent = send_email($to, $subject, $content);

        if ($sent) {
            return response()->json([
                'status' => true,
                'message' => __('Sent email successfully')
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => __('Sent email failed. Please check your SMTP info again.')
            ]);
        }

    }

    public function emailCheckerView()
    {
        return $this->getView($this->getFolderView('dashboard.email-checker'));
    }

    public function index(Request $request)
    {
        if (is_customer()) {
            $orderModel = new Order();
            $total_order = $orderModel->getTotalOrders();

            $notifyModel = new Notification();
            $total_notify = $notifyModel->getTotalNotifications();

            return $this->getView($this->getFolderView('dashboard.index'), [
                'total_order' => $total_order,
                'total_notify' => $total_notify
            ]);
        } else {
            return $this->getView($this->getFolderView('dashboard.index'));
        }
    }
}