<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class UpdaterController extends Controller
{
    public function updateDatabaseAction(Request $request)
    {
        $check_text = $request->post('check_text', '');
        if (!empty($check_text) && !is_null($check_text)) {
            if ($check_text == 'Update') {
                \Artisan::call('migrate');
                if (GMZ_VERSION == '1.0.2') {
                    \Artisan::call('db:seed --class=TaxonomySeeder');
                    update_opt('version', GMZ_VERSION);
                }
                return redirect()->back()->with('success', __('Update database successfully'));
            }
        }
        return redirect()->back()->with('error', __('Checking text is invalid'));
    }

    public function updateDatabaseView()
    {
        return $this->getView($this->getFolderView('updater.update-database'));
    }

    public function emailCheckerAction(Request $request)
    {
        $to = $request->post('email_to', '');
        $subject = $request->post('email_subject', '');
        $content = $request->post('email_content', '');
        if (empty($to) || empty($subject) || empty($content)) {
            return response()->json([
                'status' => false,
                'message' => __('Please fill all the fields')
            ]);
        }

        $sent = send_email($to, $subject, $content);
        if ($sent) {
            return response()->json([
                'status' => false,
                'message' => view('Backend::components.alert', [
                    'type' => 'success',
                    'message' => __('Sent email successfully')
                ])->render()
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => view('Backend::components.alert', [
                    'type' => 'warning',
                    'message' => __('Sent email failed. Please check your SMTP info again.')
                ])->render()
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