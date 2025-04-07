<?php
namespace App\Plugins\BraintreeGateway\Controllers;
if ( ! defined( 'GMZPATH' ) ) { exit; }

use Illuminate\Support\Facades\Validator;

class Braintree extends \BaseGateway
{
    protected $id = 'braintree';

    public function getName()
    {
        return __('Braintree');
    }

    public function getHtml()
    {
        return view('Plugin.BraintreeGateway::index', ['id' => $this->id]);
    }

    public function doPaymentCheckout($order_id)
    {
        require_once \GWBraintree::inst()->pluginPath . '/vendor/autoload.php';

        $validator = Validator::make(request()->all(),
            [
                'gmz_braintree_card_name' => 'required',
                'gmz_braintree_card_number' => 'required',
                'gmz_braintree_card_code' => 'required'
            ],
            [
                'gmz_braintree_card_name.required' => __('Card name is required'),
                'gmz_braintree_card_number.required' => __('Card number is required'),
                'gmz_braintree_card_code.required' => __('CVV code is required')
            ]
        );
        if ($validator->fails()) {
            $model = new \App\Models\Order();
            $model->query()->where('id', $order_id)->delete();
            return [
                'status' => 0,
                'message' => $validator->errors()->first()
            ];
        }

        $sandbox = get_option( 'payment_braintree_sandbox', 'on');
        if($sandbox == 'on'){
            $environment = 'sandbox';
        }else{
            $environment = 'production';
        }

        $model = new \App\Models\Order();
        $order = $model->query()->findOrFail($order_id);

        $merchant_id = get_option( 'payment_braintree_merchant_id', '');
        $public_key = get_option( 'payment_braintree_public_key', '');
        $private_key = get_option( 'payment_braintree_private_key', '');

        $total = round( (float) $order['total'], 2 );

        $config = new \Braintree\Configuration([
            'environment' => $environment,
            'merchantId' => $merchant_id,
            'publicKey' => $public_key,
            'privateKey' => $private_key
        ]);
        $gateway = new \Braintree\Gateway($config);
        $result = $gateway->transaction()->sale([
            'amount' => number_format( (float)$total, 2, '.', '' ),
            'creditCard' => [
                'number' => request()->post( 'gmz_braintree_card_number' ),
                'expirationMonth' => request()->post( 'gmz_braintree_card_expiry_month' ),
                'expirationYear' => request()->post( 'gmz_braintree_card_expiry_year' ),
                'cvv' => request()->post( 'gmz_braintree_card_code' )
            ]
        ]);

        if ($result->success) {
            return [
                'status' => true,
                'payment_status' => true,
                'transaction_id' => $result->transaction->id,
                'message' => 'Payment success!'
            ];
        } else if ($result->transaction) {
            $order->delete();
            return [
                'status' => false,
                'payment_status' => false,
                'message' => $result->transaction->processorResponseText
            ];
        } else {
            $order->delete();
            $err = [];
            foreach($result->errors->deepAll() AS $error) {
                $err[] = $error->message;
            }
            return [
                'status' => false,
                'payment_status' => false,
                'message' => implode("<br />", $err)
            ];
        }
    }

    public function settingFields()
    {
        return [
            [
                'id' => 'payment_braintree_enable',
                'label' => __('Enable'),
                'type' => 'switcher',
                'layout' => 'col-12',
                'std' => 'on',
                'break' => true,
                'tab' => 'braintree',
            ],
            [
                'id' => 'payment_braintree_name',
                'label' => __('Name'),
                'type' => 'text',
                'layout' => 'col-12',
                'std' => 'Braintree',
                'break' => true,
                'translation' => true,
                'tab' => 'braintree',
                'condition' => 'payment_braintree_enable:on'
            ],
            [
                'id' => 'payment_braintree_desc',
                'label' => __('Description'),
                'type' => 'textarea',
                'layout' => 'col-12',
                'std' => '',
                'break' => true,
                'translation' => true,
                'tab' => 'braintree',
                'condition' => 'payment_braintree_enable:on'
            ],
            [
                'id' => 'payment_braintree_logo',
                'label' => __('Logo'),
                'type' => 'image',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'break' => true,
                'tab' => 'braintree',
                'condition' => 'payment_braintree_enable:on'
            ],
            [
                'id' => 'payment_braintree_sandbox',
                'label' => __('Is Sandbox'),
                'type' => 'switcher',
                'layout' => 'col-12',
                'std' => 'on',
                'break' => true,
                'tab' => 'braintree',
                'condition' => 'payment_braintree_enable:on'
            ],
            [
                'id' => 'payment_braintree_merchant_id',
                'label' => __('Merchant ID'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'tab' => 'braintree',
                'condition' => 'payment_braintree_enable:on'
            ],
            [
                'id' => 'payment_braintree_public_key',
                'label' => __('Public Key'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'tab' => 'braintree',
                'condition' => 'payment_braintree_enable:on'
            ],
            [
                'id' => 'payment_braintree_private_key',
                'label' => __('Private Key'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'break' => true,
                'tab' => 'braintree',
                'condition' => 'payment_braintree_enable:on'
            ],
        ];
    }
}