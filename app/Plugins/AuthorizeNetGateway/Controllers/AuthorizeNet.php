<?php
namespace App\Plugins\AuthorizeNetGateway\Controllers;

use App\Plugins\AuthorizeNetGateway\Libs\Gateway;
use Illuminate\Support\Facades\Validator;
use Omnipay\Common\CreditCard;
use Omnipay\Omnipay;

class AuthorizeNet extends \BaseGateway
{
    protected $id = 'authorize_net';
    private $_gatewayObject = null;

    public function getName()
    {
        return __('Authorize.Net');
    }

    public function getHtml()
    {
        return view('Plugin.AuthorizeNetGateway::index', ['id' => $this->id]);
    }

    public function doPaymentCheckout($order_id)
    {
        require_once Gateway::inst()->pluginPath . '/vendor/autoload.php';
        $validator = Validator::make(request()->all(),
            [
                'gmz_authorize_card_name' => 'required',
                'gmz_authorize_card_number' => 'required',
                'gmz_authorize_card_code' => 'required'
            ],
            [
                'gmz_authorize_card_name.required' => __('Card name is required'),
                'gmz_authorize_card_number.required' => __('Card number is required'),
                'gmz_authorize_card_code.required' => __('CVV code is required')
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

        $this->_gatewayObject = Omnipay::create( 'AuthorizeNet_AIM' );
        $this->_gatewayObject->setApiLoginId( get_option( 'payment_authorize_net_api_login_id', '' ) );
        $this->_gatewayObject->setTransactionKey( get_option( 'payment_authorize_net_transaction_key', '' ) );

        if ( get_option( 'payment_authorize_net_sandbox') == 'on' ) {
            $this->_gatewayObject->setTestMode( true );
            $this->_gatewayObject->setDeveloperMode( true );
        }

        $model = new \App\Models\Order();
        $order = $model->query()->findOrFail($order_id);

        $currency = get_option('primary_currency', 'USD');
        $total = round( (float) $order['total'], 2 );

        $returnUrl = $this->getLinkPaymentChecking($order_id);
        $cancelUrl = $this->getLinkPaymentChecking($order_id, true);

        $purchase = [
            'card'          => new CreditCard( [
                'firstName'   => request()->post( 'gmz_authorize_card_name' ),
                'number'      => request()->post( 'gmz_authorize_card_number' ),
                'expiryMonth' => request()->post( 'gmz_authorize_card_expiry_month' ),
                'expiryYear'  => request()->post( 'gmz_authorize_card_expiry_year' ),
                'cvv'         => request()->post( 'gmz_authorize_card_code' ),
            ] ),
            'amount'        => number_format( (float)$total, 2, '.', '' ),
            'currency'      => $currency,
            'description'   => 'iBooking Reservation',
            'transactionId' => uniqid() . $order_id,
            'failureUrl'    => $cancelUrl,
            'returnUrl'     => $returnUrl,
            'cancelUrl'     => $cancelUrl
        ];

        try {
            $response = $this->_gatewayObject->purchase(
                $purchase
            )->send();

            if ( $response->isSuccessful() ) {
                return [
                    'status' => true,
                    'payment_status' => true,
                    'transaction_id' => $response->getTransactionReference(),
                    'message' => 'Payment success!'
                ];
            } elseif ( $response->isRedirect() ) {
                return [
                    'status' => true,
                    'payment_status' => false,
                    'redirect' => $response->getRedirectUrl(),
                ];
            } else {
                $model->query()->where('id', $order_id)->delete();
                return [
                    'status' => false,
                    'payment_status' => false,
                    'message' => $response->getMessage()
                ];
            }
        } catch ( \Exception $e ) {
            $model->query()->where('id', $order_id)->delete();
            return [
                'status' => false,
                'payment_status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function settingFields()
    {
        return [
            [
                'id' => 'payment_authorize_net_enable',
                'label' => __('Enable'),
                'type' => 'switcher',
                'layout' => 'col-12 col-md-12',
                'std' => 'on',
                'break' => true,
                'tab' => 'authorize_net',
            ],
            [
                'id' => 'payment_authorize_net_name',
                'label' => __('Name'),
                'type' => 'text',
                'layout' => 'col-12 col-md-12',
                'std' => 'Authorize.Net',
                'break' => true,
                'translation' => true,
                'tab' => 'authorize_net',
                'condition' => 'payment_authorize_net_enable:on'
            ],
            [
                'id' => 'payment_authorize_net_desc',
                'label' => __('Description'),
                'type' => 'textarea',
                'layout' => 'col-12 col-md-12',
                'std' => '',
                'break' => true,
                'translation' => true,
                'tab' => 'authorize_net',
                'condition' => 'payment_authorize_net_enable:on'
            ],
            [
                'id' => 'payment_authorize_net_logo',
                'label' => __('Logo'),
                'type' => 'image',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'break' => true,
                'tab' => 'authorize_net',
                'condition' => 'payment_authorize_net_enable:on'
            ],
            [
                'id' => 'payment_authorize_net_sandbox',
                'label' => __('Is Sandbox'),
                'type' => 'switcher',
                'layout' => 'col-12 col-md-12',
                'std' => 'on',
                'break' => true,
                'tab' => 'authorize_net',
                'condition' => 'payment_authorize_net_enable:on'
            ],
            [
                'id' => 'payment_authorize_net_api_login_id',
                'label' => __('API Login ID'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'tab' => 'authorize_net',
                'condition' => 'payment_authorize_net_enable:on'
            ],
            [
                'id' => 'payment_authorize_net_transaction_key',
                'label' => __('Transaction Key'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'break' => true,
                'tab' => 'authorize_net',
                'condition' => 'payment_authorize_net_enable:on'
            ],
        ];
    }
}