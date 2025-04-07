<?php


namespace App\Plugins\PayUGateway\Controllers;
if ( ! defined( 'GMZPATH' ) ) { exit; }

use Illuminate\Support\Facades\Validator;
use Omnipay\PayU\GatewayFactory;

class PayU extends \BaseGateway
{
    protected $id = 'payu';
    private $_gatewayObject = null;

    public function getName()
    {
        return __('PayU');
    }

    public function getHtml()
    {
        return view('Plugin.PayUGateway::index', ['id' => $this->id]);
    }

    public function doPaymentCheckout($order_id) {
        $model = new \App\Models\Order();
        $order = $model->query()->findOrFail($order_id);

        //Get access token
        $url = "https://secure.payu.com/pl/standard/user/oauth/authorize";
        $client_id = get_option('payment_payu_pos_id');
        $client_secret = get_option('payment_payu_client_secret');

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-Type: application/x-www-form-urlencoded",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = "grant_type=client_credentials&client_id={$client_id}&client_secret={$client_secret}";


        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

        $access_token_data = json_decode($resp, true);

        if(isset($access_token_data['error'])){
            $order->delete();
            return [
                'status' => false,
                'payment_status' => false,
                'message' => __('Payment failed')
            ];
        }elseif(isset($access_token_data['access_token'])){
            $access_token = $access_token_data['access_token'];
            //Order
            $url = "https://secure.payu.com/api/v2_1/orders";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
                "Content-Type: application/json",
                "Authorization: Bearer {$access_token}",
            );

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $returnUrl = $this->getLinkPaymentChecking($order_id);
            $cancelUrl = $this->getLinkPaymentChecking($order_id, true);
            $currency = get_option('primary_currency', 'USD');
            $total = round( (float) $order['total'], 2 );
            $site_name = get_translate(get_option('site_name', 'iBooking'));
            $description = sprintf(__('%s Reservation'), $site_name);
            $c_email = request()->post('email');
            $c_phone = request()->post('phone');
            $c_first_name = request()->post('first_name');
            $c_last_name = request()->post('last_name');

            $prod_name = sprintf(__('%s - Order #%s'), $site_name, $order->sku);


            $data = <<<DATA
{
         "notifyUrl": "{$returnUrl}",
    "continueUrl":  "{$returnUrl}",
        "customerIp": "127.0.0.1",
        "merchantPosId": "{$client_id}",
        "description": "{$description}",
        "currencyCode": "PLN",
        "totalAmount": "{$total}",
        "buyer": {
            "email": "{$c_email}",
            "phone": "{$c_phone}",
            "firstName": "{$c_first_name}",
            "lastName": "{$c_last_name}"
        },
        "products": [
            {
                "name": "{$prod_name}",
                "unitPrice": "{$total}",
                "quantity": "1"
            }
        ]
    }
DATA;

            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);

            $resp = json_decode($resp, true);

            if($resp['status']['statusCode'] == 'SUCCESS'){
                return [
                    'status' => true,
                    'payment_status' => false,
                    'redirect' => $resp['redirectUri'],
                ];
            }else{
                return [
                    'status' => false,
                    'payment_status' => false,
                    'message' => __('Payment failed')
                ];
            }
        }else{
            return [
                'status' => false,
                'payment_status' => false,
                'message' => __('Payment failed')
            ];
        }
    }

    public function settingFields()
    {
        return [
            [
                'id' => 'payment_payu_enable',
                'label' => __('Enable'),
                'type' => 'switcher',
                'layout' => 'col-12',
                'std' => 'on',
                'break' => true,
                'tab' => 'payu',
            ],
            [
                'id' => 'payment_payu_name',
                'label' => __('Name'),
                'type' => 'text',
                'layout' => 'col-12',
                'std' => 'PayU',
                'break' => true,
                'translation' => true,
                'tab' => 'payu',
                'condition' => 'payment_payu_enable:on'
            ],
            [
                'id' => 'payment_payu_desc',
                'label' => __('Description'),
                'type' => 'textarea',
                'layout' => 'col-12',
                'std' => '',
                'break' => true,
                'translation' => true,
                'tab' => 'payu',
                'condition' => 'payment_payu_enable:on'
            ],
            [
                'id' => 'payment_payu_logo',
                'label' => __('Logo'),
                'type' => 'image',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'break' => true,
                'tab' => 'payu',
                'condition' => 'payment_payu_enable:on'
            ],
            [
                'id' => 'payment_payu_sandbox',
                'label' => __('Is Sandbox'),
                'type' => 'switcher',
                'layout' => 'col-12',
                'std' => 'on',
                'break' => true,
                'tab' => 'payu',
                'condition' => 'payment_payu_enable:on'
            ],
            [
                'id' => 'payment_payu_pos_id',
                'label' => __('Pos ID'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'tab' => 'payu',
                'condition' => 'payment_payu_enable:on'
            ],
            [
                'id' => 'payment_payu_second_key',
                'label' => __('Second Key'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'tab' => 'payu',
                'condition' => 'payment_payu_enable:on'
            ],
            [
                'id' => 'payment_payu_client_secret',
                'label' => __('Client Secret'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'break' => true,
                'tab' => 'payu',
                'condition' => 'payment_payu_enable:on'
            ],
        ];
    }
}