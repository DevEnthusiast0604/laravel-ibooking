<?php


use Illuminate\Support\Facades\Session;

if(!class_exists('Cart')) {
	class Cart {
		private $_cart_key = 'gmz_cart';
		private static $_inst;

		public function getKey() {
			return $this->_cart_key;
		}

		public function removeCart() {
			Session::remove( $this->getKey() );
		}

		public function getCart() {
			return Session::get( $this->getKey() );
		}

		public function setCart( $data ) {
			Session::put( $this->getKey(), $data );
		}

		public static function inst() {
			if ( empty( self::$_inst ) ) {
				self::$_inst = new self();
			}

			return self::$_inst;
		}
	}
}