<?php

use Omnipay\Omnipay;

if(!class_exists('Stripe')) {
	class Stripe extends BaseGateway {
		protected $id = 'stripe';
		private $_gatewayObject = null;

		public function getName() {
			return __( 'Stripe' );
		}

		public function getHtml() {
			return view( 'Frontend::gateways.stripe', [ 'id' => $this->id ] );
		}

		private function initGateway() {
			$api_key              = get_option( 'payment_stripe_secret_key' );
			$this->_gatewayObject = Omnipay::create( 'Stripe' );
			$this->_gatewayObject->setApiKey( $api_key );
		}

		public function doPaymentCheckout( $order_id ) {
			$model = new \App\Models\Order();
			$order = $model->query()->findOrFail( $order_id );
			$this->initGateway();

			$stripeToken = $order['token_code'];

			$response = $this->_gatewayObject->purchase( [
				'amount'               => $order['total'],
				'currency'             => 'USD',
				'token'                => $stripeToken,
				'statement_descriptor' => 'ORDER' . $order['sku'],
			] )->send();

			if ( $response->isSuccessful() ) {
				return [
					'status'         => true,
					'payment_status' => true,
					'transaction_id' => $response->getTransactionReference(),
					'message'        => 'Payment success!'
				];
			}

			// payment failed
			return [
				'status'         => false,
				'payment_status' => false,
				'message'        => $response->getMessage()
			];
		}

		public function settingFields() {
			return [
				[
					'id'     => 'payment_stripe_enable',
					'label'  => __( 'Enable' ),
					'type'   => 'switcher',
					'layout' => 'col-12',
					'std'    => 'on',
					'break'  => true,
					'tab'    => 'stripe',
				],
				[
					'id'          => 'payment_stripe_name',
					'label'       => __( 'Name' ),
					'type'        => 'text',
					'layout'      => 'col-12',
					'std'         => 'Stripe',
					'break'       => true,
					'translation' => true,
					'tab'         => 'stripe',
					'condition'   => 'payment_stripe_enable:on'
				],
				[
					'id'          => 'payment_stripe_desc',
					'label'       => __( 'Description' ),
					'type'        => 'textarea',
					'layout'      => 'col-12',
					'std'         => '',
					'break'       => true,
					'translation' => true,
					'tab'         => 'stripe',
					'condition'   => 'payment_stripe_enable:on'
				],
				[
					'id'        => 'payment_stripe_logo',
					'label'     => __( 'Logo' ),
					'type'      => 'image',
					'layout'    => 'col-12 col-md-6',
					'std'       => '',
					'break'     => true,
					'tab'       => 'stripe',
					'condition' => 'payment_stripe_enable:on'
				],
				[
					'id'        => 'payment_stripe_api_key',
					'label'     => __( 'Publishable key' ),
					'type'      => 'text',
					'layout'    => 'col-12 col-md-6',
					'tab'       => 'stripe',
					'condition' => 'payment_stripe_enable:on'
				],
				[
					'id'        => 'payment_stripe_secret_key',
					'label'     => __( 'Secret key' ),
					'type'      => 'text',
					'layout'    => 'col-12 col-md-6',
					'break'     => true,
					'tab'       => 'stripe',
					'condition' => 'payment_stripe_enable:on'
				],
			];
		}
	}
}