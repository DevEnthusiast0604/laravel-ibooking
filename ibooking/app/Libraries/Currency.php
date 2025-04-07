<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/13/20
 * Time: 20:15
 */

use  Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

if(!class_exists('Currency')) {
	class Currency {
		private $sessionCurrency = 'gmz_currency';

		public function __construct() {
			$this->_setDefaultCurrency();
		}

		public function convertPrice( $price, $show_symbol = true, $format = true, $_currency = null ) {
			$currency_list = list_currencies();
			$currency      = $this->currentCurrency();
			if ( $currency ) {
				$currency = isset( $currency['unit'] ) ? $currency['unit'] : '';
			}
			if ( ! empty( $_currency ) ) {
				$currency = $_currency['unit'];
			}

			$rate               = 1;
			$symbol             = '';
			$currency_position  = 'right';
			$decimal            = 2;
			$thousand_separator = ',';
			$decimal_separator  = '.';

			if ( ! empty( $currency_list ) && $currency ) {
				foreach ( $currency_list as $item ) {
					$unit = trim( $item['unit'] );
					if ( $currency == $unit ) {
						$rate               = (float) $item['exchange'];
						$currency_position  = esc_html( $item['position'] );
						$symbol             = esc_html( $item['symbol'] );
						$decimal            = (int) $item['currency_decimal'];
						$thousand_separator = esc_html( $item['thousand_separator'] );
						$decimal_separator  = esc_html( $item['decimal_separator'] );
						break;
					}
				}
			}

			$price = (float) $price * $rate;

			$price = round( $price, $decimal );

			if ( $format ) {
				$price = number_format( $price, $decimal, $decimal_separator, $thousand_separator );
			}
			if ( ! $show_symbol ) {
				return $price;
			} else {
				if ( $currency_position == 'right' ) {
					return $price . $symbol;
				} elseif ( $currency_position == 'right_space' ) {
					return $price . ' ' . $symbol;
				} elseif ( $currency_position == 'left_space' ) {
					return $symbol . ' ' . $price;
				} else {
					return $symbol . $price;
				}
			}
		}

		public function getSymbolCurrency( $position = true ) {
			$currency_list = list_currencies();
			$currency      = $this->currentCurrency();
			if ( $currency ) {
				$currency = isset( $currency['unit'] ) ? $currency['unit'] : '';
			}
			if ( ! empty( $_currency ) ) {
				$currency = $_currency['unit'];
			}

         $rate               = 1;
         $symbol             = '';
         $currency_position  = 'right';
         $decimal            = 2;
         $thousand_separator = ',';
         $decimal_separator  = '.';

			if ( ! empty( $currency_list ) && $currency ) {
				foreach ( $currency_list as $item ) {
					$unit = trim( $item['unit'] );
					if ( $currency == $unit ) {
						$currency_position = $item['position'];
						$symbol            = $item['symbol'];
                  $decimal            = (int) $item['currency_decimal'];
                  $thousand_separator = esc_html( $item['thousand_separator'] );
                  $decimal_separator  = esc_html( $item['decimal_separator'] );
						break;
					}
				}
			}
			if ( $position ) {
				return [
					'position' => $currency_position,
					'symbol'   => $symbol,
               'decimal' => $decimal,
               'thousand_separator' => $thousand_separator,
               'decimal_separator' => $decimal_separator,
				];
			} else {
				return $symbol;
			}

		}

		public function _setDefaultCurrency() {
			$get_currency = request()->get( 'currency', '' );
			if ( ! empty( $get_currency ) ) {
				$get_currency   = strtolower( $get_currency );
				$all_currencies = list_currencies();
				if ( ! empty( $all_currencies ) ) {
					foreach ( $all_currencies as $key => $val ) {
						if ( $get_currency == trim( strtolower( $val['unit'] ) ) ) {
							$this->_setCurrency( $val );
							break;
						}
					}
				}
			} else {
				if ( ! $this->currentCurrency() ) {
					$primaryCurrency = $this->primaryCurrency();
					$this->_setCurrency( $primaryCurrency );
				}
			}
		}

		private function _setCurrency( $currency ) {
			if ( $currency ) {
				Session::put( $this->sessionCurrency, $currency );
			} else {
				Session::put( $this->sessionCurrency, [
					'name'               => 'USD',
					'unit'               => 'USD',
					'symbol'             => '$',
					'exchange'           => '1',
					'position'           => 'left',
					'thousand_separator' => ',',
					'decimal_separator'  => '.',
					'currency_decimal'   => '2'
				] );
			}
		}

		public function currentCurrency( $key = '' ) {
			$currency = Session::get( $this->sessionCurrency );

			if ( is_null( $currency ) ) {
				$currency = $this->primaryCurrency();
			}

			if ( $key ) {
				return isset( $currency[ $key ] ) ? $currency[ $key ] : false;
			}

			return $currency;
		}

		public function primaryCurrency() {
			$base_currency = get_option( 'primary_currency', [] );
			if ( empty( $base_currency ) ) {
				return false;
			}
			$list_currency = list_currencies();
			if ( ! empty( $list_currency ) ) {
				foreach ( $list_currency as $key => $item ) {
					if ( $base_currency === trim( $item['unit'] ) ) {
						$base_currency = $item;

						return $base_currency;
					}
				}
			}
			if ( ! empty( $base_currency ) ) {
				return $base_currency;
			}

			return false;
		}

		public static function get_inst() {
			static $instance;
			if ( is_null( $instance ) ) {
				$instance = new self();
			}

			return $instance;
		}
	}
}
