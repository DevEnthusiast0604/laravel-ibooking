<?php
namespace App\Plugins\SkrillGateway\Controllers;

use App\Plugins\SkrillGateway\Libs\Gateway;
use Obydul\LaraSkrill\SkrillClient;
use Obydul\LaraSkrill\SkrillRequest;

class Skrill extends \BaseGateway
{
    protected $id = 'skrill';
    private $_gatewayObject = null;

    public function getName()
    {
        return __('Skrill');
    }

    public function getHtml()
    {
        return view('Plugin.SkrillGateway::index', ['id' => $this->id]);
    }

    public function doPaymentCheckout($order_id)
    {
        require_once Gateway::inst()->pluginPath . '/vendor/autoload.php';

        $model = new \App\Models\Order();
        $order = $model->query()->findOrFail($order_id);
        $currency = get_option('primary_currency', 'USD');
        $total = round( (float) $order['total'], 2 );

        $returnUrl = $this->getLinkPaymentChecking($order_id);
        $cancelUrl = $this->getLinkPaymentChecking($order_id, true);

        $site_name = get_option('site_name', 'iBooking');
        $customer_email = request()->post('email');

        $this->_gatewayObject = new SkrillRequest();
        $this->_gatewayObject->pay_to_email = get_option('payment_skrill_email');
        $this->_gatewayObject->return_url = $returnUrl;
        $this->_gatewayObject->cancel_url = $cancelUrl;
        $this->_gatewayObject->logo_url = get_logo();
        $this->_gatewayObject->status_url = 'email or ipn';
        $this->_gatewayObject->status_url2 = 'email or ipn';

        $this->_gatewayObject->transaction_id = $order['sku'];
        $this->_gatewayObject->amount = number_format((float)$total, 2, '.', '');
        $this->_gatewayObject->currency = $currency;
        $this->_gatewayObject->language = 'EN';
        $this->_gatewayObject->prepare_only = '1';
        $this->_gatewayObject->merchant_fields = $site_name . ', ' . $customer_email;
        $this->_gatewayObject->site_name = get_option('site_name', 'iBooking');
        $this->_gatewayObject->customer_email = $customer_email;
        $this->_gatewayObject->detail1_description = 'iBooking Reservation';
        $this->_gatewayObject->detail1_text = '101';


        $client = new SkrillClient($this->_gatewayObject);
        $sid = $client->generateSID(); //return SESSION ID

        $jsonSID = json_decode($sid);
        if ($jsonSID != null && $jsonSID->code == "BAD_REQUEST") {
            $order->delete();
            return [
                'status' => false,
                'payment_status' => false,
                'message' => $jsonSID->message
            ];
        }

        return [
            'status' => true,
            'payment_status' => false,
            'redirect' => $client->paymentRedirectUrl($sid),
        ];
    }

    public function settingFields()
    {
        return [
            [
                'id' => 'payment_skrill_enable',
                'label' => __('Enable'),
                'type' => 'switcher',
                'layout' => 'col-12 col-md-12',
                'std' => 'on',
                'break' => true,
                'tab' => 'skrill',
            ],
            [
                'id' => 'payment_skrill_name',
                'label' => __('Name'),
                'type' => 'text',
                'layout' => 'col-12 col-md-12',
                'std' => 'Skrill',
                'break' => true,
                'translation' => true,
                'tab' => 'skrill',
                'condition' => 'payment_skrill_enable:on'
            ],
            [
                'id' => 'payment_skrill_desc',
                'label' => __('Description'),
                'type' => 'textarea',
                'layout' => 'col-12 col-md-12',
                'std' => '',
                'break' => true,
                'translation' => true,
                'tab' => 'skrill',
                'condition' => 'payment_skrill_enable:on'
            ],
            [
                'id' => 'payment_skrill_logo',
                'label' => __('Logo'),
                'type' => 'image',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'break' => true,
                'tab' => 'skrill',
                'condition' => 'payment_skrill_enable:on'
            ],
            [
                'id' => 'payment_skrill_email',
                'label' => __('Skrill Email'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'tab' => 'skrill',
                'condition' => 'payment_skrill_enable:on'
            ],
            [
                'id' => 'payment_skrill_password',
                'label' => __('Skrill Password'),
                'type' => 'text',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'break' => true,
                'tab' => 'skrill',
                'condition' => 'payment_skrill_enable:on'
            ],
        ];
    }
}