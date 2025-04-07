<?php
namespace App\Plugins\SecurionpayGateway\Controllers;
if ( ! defined( 'GMZPATH' ) ) { exit; }
use Illuminate\Http\Request;

class Securionpay extends \BaseGateway
{
    protected $id = 'securionpay';

    public function getName()
    {
        return __('Securionpay');
    }

    public function securionpayErrorAction(Request $request){
        $id = $request->post('_id', '');
        if(!empty($id)){
            $model = new \App\Models\Order();
            $order = $model->query()->find($id);
            if($order){
                $order->delete();
            }
        }
        return response()->json([
            'status' => true
        ]);
    }

    public function securionpaySuccessAction(Request $request){
        $id = $request->post('_id', '');
        $result = $request->post('_result', '');
        if(!empty($id) && !empty($result)){
            $model = new \App\Models\Order();
            $order = $model->query()->find($id);
            if($order){
                $order->update([
                    'payment_status' => 1,
                    'status' => GMZ_PAYMENT_COMPLETED
                ]);
                return response()->json([
                    'status' => true,
                    'redirect' => $this->getLinkPaymentChecking($id)
                ]);
            }
        }
        return response()->json([
            'status' => false,
            'message' => __('Have an error when doing it')
        ]);
    }

    public function getHtml()
    {
        return view('Plugin.SecurionpayGateway::index', ['id' => $this->id]);
    }

    public function doPaymentCheckout($order_id)
    {
        return [
            'status' => true,
            'payment_status' => false,
        ];
    }

    public function settingFields()
    {
        return [
            [
                'id' => 'payment_securionpay_enable',
                'label' => __('Enable'),
                'type' => 'switcher',
                'layout' => 'col-12',
                'std' => 'on',
                'break' => true,
                'tab' => 'securionpay',
            ],
            [
                'id' => 'payment_securionpay_name',
                'label' => __('Name'),
                'type' => 'text',
                'layout' => 'col-12',
                'std' => 'Securionpay',
                'break' => true,
                'translation' => true,
                'tab' => 'securionpay',
                'condition' => 'payment_securionpay_enable:on'
            ],
            [
                'id' => 'payment_securionpay_desc',
                'label' => __('Description'),
                'type' => 'textarea',
                'layout' => 'col-12',
                'std' => '',
                'break' => true,
                'translation' => true,
                'tab' => 'securionpay',
                'condition' => 'payment_securionpay_enable:on'
            ],
            [
                'id' => 'payment_securionpay_logo',
                'label' => __('Logo'),
                'type' => 'image',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'break' => true,
                'tab' => 'securionpay',
                'condition' => 'payment_securionpay_enable:on'
            ],
            [
                'id' => 'payment_securionpay_public_key',
                'label' => __('Public Key'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'tab' => 'securionpay',
                'condition' => 'payment_securionpay_enable:on'
            ],
            [
                'id' => 'payment_securionpay_secret_key',
                'label' => __('Secret Key'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'break' => true,
                'tab' => 'securionpay',
                'condition' => 'payment_securionpay_enable:on'
            ]
        ];
    }
}