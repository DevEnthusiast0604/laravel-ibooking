<?php
namespace App\Plugins\BluesnapGateway\Controllers;

use App\Plugins\BluesnapGateway\Libs\Gateway;
use Illuminate\Support\Facades\Validator;

class Bluesnap extends \BaseGateway
{
    protected $id = 'bluesnap';

    public function getName()
    {
        return __('BlueSnap');
    }

    public function getHtml()
    {
        return view('Plugin.BluesnapGateway::index', ['id' => $this->id]);
    }

    public function doPaymentCheckout($order_id)
    {
        require_once Gateway::inst()->pluginPath . '/vendor/autoload.php';

        $validator = Validator::make(request()->all(),
            [
                'gmz_bluesnap_card_name' => 'required',
                'gmz_bluesnap_card_number' => 'required',
                'gmz_bluesnap_card_code' => 'required'
            ],
            [
                'gmz_bluesnap_card_name.required' => __('Card name is required'),
                'gmz_bluesnap_card_number.required' => __('Card number is required'),
                'gmz_bluesnap_card_code.required' => __('CVV code is required')
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

        $sandbox = get_option( 'payment_bluesnap_sandbox', 'on');
        $api_key = get_option( 'payment_bluesnap_api_key', '');
        $api_password = get_option( 'payment_bluesnap_api_password', '');
        if($sandbox == 'on'){
            $environment = 'sandbox';
        }else{
            $environment = 'production';
        }

        \tdanielcox\Bluesnap\Bluesnap::init($environment, $api_key, $api_password);

        $model = new \App\Models\Order();
        $order = $model->query()->findOrFail($order_id);

        $currency = get_option('primary_currency', 'USD');
        $total = round( (float) $order['total'], 2 );

        $response = \tdanielcox\Bluesnap\CardTransaction::create([
            'creditCard' => [
                'cardNumber' => request()->post( 'gmz_bluesnap_card_number' ),
                'expirationMonth' => request()->post( 'gmz_bluesnap_card_expiry_month' ),
                'expirationYear' => request()->post( 'gmz_bluesnap_card_expiry_year' ),
                'securityCode' => request()->post( 'gmz_bluesnap_card_code' )
            ],
            'amount' => number_format( (float)$total, 2, '.', '' ),
            'currency' => $currency,
            'recurringTransaction' => 'ECOMMERCE',
            'cardTransactionType' => 'AUTH_CAPTURE',
        ]);

        if ($response->failed())
        {
            $error = $response->data;
            $order->delete();
            return [
                'status' => false,
                'payment_status' => false,
                'message' => $error
            ];
        }

        $transaction = $response->data;

        return [
            'status' => true,
            'payment_status' => true,
            'transaction_id' => $transaction->id,
            'message' => 'Payment success!'
        ];
    }

    public function settingFields()
    {
        return [
            [
                'id' => 'payment_bluesnap_enable',
                'label' => __('Enable'),
                'type' => 'switcher',
                'layout' => 'col-12 col-md-12',
                'std' => 'on',
                'break' => true,
                'tab' => 'bluesnap',
            ],
            [
                'id' => 'payment_bluesnap_name',
                'label' => __('Name'),
                'type' => 'text',
                'layout' => 'col-12 col-md-12',
                'std' => 'BlueSnap',
                'break' => true,
                'translation' => true,
                'tab' => 'bluesnap',
                'condition' => 'payment_bluesnap_enable:on'
            ],
            [
                'id' => 'payment_bluesnap_desc',
                'label' => __('Description'),
                'type' => 'textarea',
                'layout' => 'col-12 col-md-12',
                'std' => '',
                'break' => true,
                'translation' => true,
                'tab' => 'bluesnap',
                'condition' => 'payment_bluesnap_enable:on'
            ],
            [
                'id' => 'payment_bluesnap_logo',
                'label' => __('Logo'),
                'type' => 'image',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'break' => true,
                'tab' => 'bluesnap',
                'condition' => 'payment_bluesnap_enable:on'
            ],
            [
                'id' => 'payment_bluesnap_sandbox',
                'label' => __('Is Sandbox'),
                'type' => 'switcher',
                'layout' => 'col-12 col-md-12',
                'std' => 'on',
                'break' => true,
                'tab' => 'bluesnap',
                'condition' => 'payment_bluesnap_enable:on'
            ],
            [
                'id' => 'payment_bluesnap_api_key',
                'label' => __('API Key'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'tab' => 'bluesnap',
                'condition' => 'payment_bluesnap_enable:on'
            ],
            [
                'id' => 'payment_bluesnap_api_password',
                'label' => __('API Password'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'break' => true,
                'tab' => 'bluesnap',
                'condition' => 'payment_bluesnap_enable:on'
            ],
        ];
    }
}