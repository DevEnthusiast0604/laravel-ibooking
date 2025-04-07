<?php
namespace App\Plugins\SubmitFormGateway\Controllers;
if ( ! defined( 'GMZPATH' ) ) { exit; }

class SubmitForm extends \BaseGateway
{
    protected $id = 'submit_form';

    public function getName()
    {
        return __('Submit Form');
    }

    public function getHtml()
    {
        return view('Plugin.SubmitFormGateway::index', ['id' => $this->id]);
    }

    public function doPaymentCheckout($order_id)
    {
        $returnURL = $this->getLinkPaymentChecking($order_id, false, false);

        \Cart::inst()->removeCart();

        return [
            'status' => true,
            'payment_status' => false,
            'redirect' => $returnURL
        ];
    }

    public function settingFields()
    {
        return [
            [
                'id' => 'payment_submit_form_enable',
                'label' => __('Enable'),
                'type' => 'switcher',
                'layout' => 'col-12 col-md-12',
                'std' => 'on',
                'break' => true,
                'tab' => 'submit_form',
            ],
            [
                'id' => 'payment_submit_form_name',
                'label' => __('Name'),
                'type' => 'text',
                'layout' => 'col-12 col-md-12',
                'std' => 'Submit Form',
                'break' => true,
                'translation' => true,
                'tab' => 'submit_form',
                'condition' => 'payment_submit_form_enable:on'
            ],
            [
                'id' => 'payment_submit_form_desc',
                'label' => __('Description'),
                'type' => 'textarea',
                'layout' => 'col-12 col-md-12',
                'std' => '',
                'break' => true,
                'translation' => true,
                'tab' => 'submit_form',
                'condition' => 'payment_submit_form_enable:on'
            ],
            [
                'id' => 'payment_submit_form_logo',
                'label' => __('Logo'),
                'type' => 'image',
                'layout' => 'col-12 col-md-6',
                'std' => '',
                'break' => true,
                'tab' => 'submit_form',
                'condition' => 'payment_submit_form_enable:on'
            ]
        ];
    }
}