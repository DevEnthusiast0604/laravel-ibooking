<?php

if(!class_exists('BaseGateway')) {
	class BaseGateway {
		static $_inst;
		protected $id;

		public function checkCompleteIsRequired() {
			return false;
		}

		public function checkCompletePurchase( $order_id, $total ) {
			return array(
				'status' => true
			);
		}

		public function doPaymentCheckout( $order_id ) {
			return [
				'status'         => false,
				'redirect'       => false,
				'payment_status' => false,
				'transaction_id' => false,
				'message'        => false,
			];
		}

		public function doCheckout( $payment_used, $order_id ) {
			$orderModel = new \App\Models\Order();
			$order      = $orderModel->query()->find( $order_id );

			if ( empty( $order ) ) {
				$response = array(
					'status'  => false,
					'message' => __( 'The order is not exists.' )
				);
			}

			$response = $payment_used->doPaymentCheckout( $order_id );

			if ( ! empty( $response['redirect'] ) ) {
				return $response;
			} else {
				if ( ! empty( $response['payment_status'] ) && isset( $response['transaction_id'] ) ) {

					//remove cart
					\Cart::inst()->removeCart();

					//update payment status of order
					$orderModel->query()
					           ->where( 'id', $order_id )
					           ->update( [
						           'payment_status' => GMZ_PAYMENT_COMPLETED,
						           'status'         => GMZ_STATUS_COMPLETE,
						           'transaction_id' => $response['transaction_id']
					           ] );
					$orderModel->appendChangeLog( $order_id, 'system', 'payment success' );

					add_money_to_wallet( $order_id );

					// Returns ajax data
					// Go to OrderControler@PaymentChecking
					$response['redirect'] = $this->getLinkPaymentChecking( $order_id );

					return $response;
				} else {
					$message = isset( $response['message'] ) ? $response['message'] : 'No message';
					$orderModel->appendChangeLog( $order_id, 'system', $message );

					return $response;
				}
			}

		}

		public function updateOrder() {

		}


		public function setDefaultParams( $booking ) {
		}

		public function getName() {

		}

		public function getIcon() {

		}

		function getID() {
			return $this->id;
		}


		public function isEnable() {
			$enabled = get_option( 'payment_' . $this->id . '_enable', 'on' );
			if ( $enabled == 'on' ) {
				return true;
			} else {
				return false;
			}
		}

		public function getHtml() {
			return '';
		}


		public function checkoutValidate( $cart, $booking_obj ) {
			return $cart;
		}

		public function getLinkPaymentChecking( $order_id, $cancel = false, $checking = true ) {
			if ( $checking == true ) {
				$payment_checking = url( 'payment-checking' );
			} else {
				$payment_checking = url( 'complete-order' );
			}

			if ( $order_id ) {
				$model       = new \App\Models\Order();
				$order       = $model->query()->where( 'id', $order_id )->first();
				$order_token = $order['order_token'];
				$status      = '1';
				if ( $cancel ) {
					$status = '0';
				}
				if ( ! $order_token ) {
					$array = [
						'order_id' => $order_id,
						'status'   => $status
					];
				} else {
					$array = [
						'order_token' => $order_token,
						'status'      => $status
					];
				}
				$payment_checking = add_query_arg( $array, $payment_checking );
			}

			return $payment_checking;
		}

		public function settingFields() {
			return [];
		}

		public function getPaymentSettings() {
			$settings          = [];
			$gateways          = Gateway::inst()->getGateways();
			$payment_structure = get_opt( 'payment_structure', [] );
			if ( ! empty( $gateways ) ) {
				foreach ( $gateways as $gateway ) {
					$settings[ $gateway->getID() ] = [
						'id'      => $gateway->getID(),
						'heading' => $gateway->getName(),
						'fields'  => $gateway->settingFields()
					];
				}
			}

			if ( ! empty( $payment_structure ) ) {
				$payment_structure      = json_decode( $payment_structure );
				$settings_keys          = array_keys( $settings );
				$payment_structure_temp = [];
				foreach ( $payment_structure as $ps ) {
					if ( in_array( $ps, $settings_keys ) ) {
						$payment_structure_temp[] = $ps;
					}
				}
				$settings = array_merge( array_flip( $payment_structure_temp ), $settings );
			}

			return $settings;
		}

		public static function inst() {
			if ( empty( self::$_inst ) ) {
				self::$_inst = new self();
			}

			return self::$_inst;
		}
	}
}