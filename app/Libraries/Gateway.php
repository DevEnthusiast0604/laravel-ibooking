<?php
use TorMorten\Eventy\Facades\Events as Eventy;

if(!class_exists('Gateway')) {
	class Gateway {
		private static $_inst;
		private $_gatewayInstances = [];

		public function __construct() {
			$this->_registerPaymentGateways();
		}

		public function getPaymentsAvailable() {
			$payments = $this->getGateways();
			$res      = array();
			if ( ! empty( $payments ) ) {
				foreach ( $payments as $k => $v ) {
					$payment_enable = $v->isEnable();
					if ( $payment_enable ) {
						$name      = get_option( 'payment_' . $k . '_name', $v->getName() );
						$logo      = get_attachment_url( get_option( 'payment_' . $k . '_logo' ) );
						$desc      = get_option( 'payment_' . $k . '_desc' );
						$res[ $k ] = array(
							'id'   => $k,
							'name' => $name,
							'logo' => $logo,
							'desc' => $desc,
							'html' => $v->getHtml()
						);
					}
				}
			}

			if ( ! empty( $res ) ) {
				$payment_structure = get_opt( 'payment_structure', [] );
				if ( ! empty( $payment_structure ) ) {
					$payment_structure      = json_decode( $payment_structure );
					$settings_keys          = array_keys( $res );
					$payment_structure_temp = [];
					foreach ( $payment_structure as $ps ) {
						if ( in_array( $ps, $settings_keys ) ) {
							$payment_structure_temp[] = $ps;
						}
					}
					$res = array_merge( array_flip( $payment_structure_temp ), $res );
				}
			}

			return $res;
		}

		public function getGateway( $gateway ) {
			if ( ! isset( $this->_gatewayInstances[ $gateway ] ) ) {
				return false;
			}

			return $this->_gatewayInstances[ $gateway ];
		}

		public function getGateways() {
			return $this->_gatewayInstances;
		}

		public function _registerPaymentGateways() {
			$gateways = glob( __DIR__ . '/../Gateways/*' );
			if ( ! empty( $gateways ) ) {
				foreach ( $gateways as $item ) {
					if ( file_exists( $item ) ) {
						$base_name = basename( $item );
						if ( $base_name != 'BaseGateway.php' ) {
							include_once $item;
							$class_name = str_replace( '.php', '', $base_name );
							if ( class_exists( $class_name ) ) {
								$obj                                      = new $class_name();
								$this->_gatewayInstances[ $obj->getID() ] = $obj;
							}
						}
					}
				}
			}
			$this->_gatewayInstances = Eventy::filter( 'gmz_gateways', $this->_gatewayInstances );
		}


		public static function inst() {
			if ( empty( self::$_inst ) ) {
				self::$_inst = new self();
			}

			return self::$_inst;
		}
	}
}