<?php

use Omnipay\Omnipay;

if(!class_exists('Paypal')) {
	class Paypal extends BaseGateway {
		protected $id = 'paypal';
		private $_gatewayObject = null;

		public function getName() {
			return __( 'Paypal' );
		}

		public function getHtml() {
			return view( 'Frontend::gateways.paypal', [ 'id' => $this->id ] );
		}

		public function checkCompleteIsRequired() {
			return true;
		}

		private function initGateway() {
			$client_id  = get_option( 'payment_paypal_client_id' );
			$secret_key = get_option( 'payment_paypal_secret_key' );
			$test_mode  = get_option( 'payment_paypal_testmode', 'on' );

			if ( $test_mode == 'on' ) {
				$test_mode = true;
			} else {
				$test_mode = false;
			}

			$this->_gatewayObject = Omnipay::create( 'PayPal_Rest' );
			$this->_gatewayObject->setClientId( $client_id );
			$this->_gatewayObject->setSecret( $secret_key );
			$this->_gatewayObject->setTestMode( $test_mode );
		}

		public function checkCompletePurchase( $order_id, $total ) {
			$model = new \App\Models\Order();
			$this->initGateway();
			$payment_id = request()->get( 'paymentId' );

			$response = $this->_gatewayObject->completePurchase( [
				'payerId'              => request()->get( 'PayerID' ),
				'transactionReference' => $payment_id,
			] )->send();
			if ( $response->isSuccessful() ) {
				//is payment success
				return [
					'payment_status' => true,
					'message'        => 'payment success',
					'transaction_id' => $payment_id
				];
			} else {
				//is payment failed
				return [
					'payment_status' => false,
					'message'        => $response->getMessage(),
					'transaction_id' => null,
				];
			}

		}

		public function doPaymentCheckout( $order_id ) {
			$model = new \App\Models\Order();
			$order = $model->query()->find( $order_id );
			$this->initGateway();

			$returnUrl = $this->getLinkPaymentChecking( $order_id );
			$cancelUrl = $this->getLinkPaymentChecking( $order_id, true );

			$response = $this->_gatewayObject->purchase( [
				'amount'        => number_format( $order['total'], 2, '.', '' ),
				'transactionId' => "ORDER" . $order['sku'],
				'currency'      => 'USD',
				'cancelUrl'     => $cancelUrl,
				'returnUrl'     => $returnUrl,
			] )->send();

			if ( $response->isRedirect() ) {
				//is success
				return [
					'status'         => true,
					'payment_status' => false,
					'redirect'       => $response->getRedirectUrl(),
				];
			} else {
				//is failed
				return array(
					'status'         => false,
					'payment_status' => false,
					'message'        => $response->getMessage()
				);
			}
		}

		public function settingFields() {
			return [
				[
					'id'     => 'payment_paypal_enable',
					'label'  => __( 'Enable' ),
					'type'   => 'switcher',
					'layout' => 'col-12 col-md-12',
					'std'    => 'on',
					'break'  => true,
					'tab'    => 'paypal',
				],
				[
					'id'          => 'payment_paypal_name',
					'label'       => __( 'Name' ),
					'type'        => 'text',
					'layout'      => 'col-12',
					'std'         => 'Paypal',
					'break'       => true,
					'translation' => true,
					'tab'         => 'paypal',
					'condition'   => 'payment_paypal_enable:on'
				],
				[
					'id'          => 'payment_paypal_desc',
					'label'       => __( 'Description' ),
					'type'        => 'textarea',
					'layout'      => 'col-12',
					'std'         => '',
					'break'       => true,
					'translation' => true,
					'tab'         => 'paypal',
					'condition'   => 'payment_paypal_enable:on'
				],
				[
					'id'        => 'payment_paypal_logo',
					'label'     => __( 'Logo' ),
					'type'      => 'image',
					'layout'    => 'col-12 col-md-6',
					'std'       => '',
					'break'     => true,
					'tab'       => 'paypal',
					'condition' => 'payment_paypal_enable:on'
				],
				[
					'id'        => 'payment_paypal_testmode',
					'label'     => __( 'Test Mode' ),
					'type'      => 'switcher',
					'std'       => 'on',
					'layout'    => 'col-12',
					'break'     => true,
					'tab'       => 'paypal',
					'condition' => 'payment_paypal_enable:on'
				],
				[
					'id'        => 'payment_paypal_client_id',
					'label'     => __( 'Client ID' ),
					'type'      => 'text',
					'layout'    => 'col-12 col-md-6',
					'tab'       => 'paypal',
					'condition' => 'payment_paypal_enable:on'
				],
				[
					'id'        => 'payment_paypal_secret_key',
					'label'     => __( 'Secret key' ),
					'type'      => 'text',
					'layout'    => 'col-12 col-md-6',
					'break'     => true,
					'tab'       => 'paypal',
					'condition' => 'payment_paypal_enable:on'
				],
			];
		}
	}
}