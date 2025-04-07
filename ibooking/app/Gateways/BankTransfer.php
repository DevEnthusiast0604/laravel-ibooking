<?php
if(!class_exists('BankTransfer')) {
	class BankTransfer extends BaseGateway {
		protected $id = 'bank_transfer';
		private $_gatewayObject = null;

		public function getName() {
			return __( 'Bank Transfer' );
		}

		public function getHtml() {
			return view( 'Frontend::gateways.bank_transfer', [ 'id' => $this->id ] );
		}

		public function doPaymentCheckout( $order_id ) {
			$returnURL = $this->getLinkPaymentChecking( $order_id, false, false );

			//remove cart
			//\Cart::inst()->removeCart();

			return [
				'status'         => true,
				'payment_status' => false,
				'redirect'       => $returnURL
			];
		}

		public function settingFields() {
			return [
				[
					'id'     => 'payment_bank_transfer_enable',
					'label'  => __( 'Enable' ),
					'type'   => 'switcher',
					'layout' => 'col-12',
					'std'    => 'on',
					'break'  => true,
					'tab'    => 'bank_transfer',
				],
				[
					'id'          => 'payment_bank_transfer_name',
					'label'       => __( 'Name' ),
					'type'        => 'text',
					'layout'      => 'col-12',
					'std'         => 'Bank Transfer',
					'break'       => true,
					'translation' => true,
					'tab'         => 'bank_transfer',
					'condition'   => 'payment_bank_transfer_enable:on'
				],
				[
					'id'          => 'payment_bank_transfer_desc',
					'label'       => __( 'Description' ),
					'type'        => 'textarea',
					'layout'      => 'col-12',
					'std'         => '',
					'break'       => true,
					'translation' => true,
					'tab'         => 'bank_transfer',
					'condition'   => 'payment_bank_transfer_enable:on'
				],
				[
					'id'        => 'payment_bank_transfer_logo',
					'label'     => __( 'Logo' ),
					'type'      => 'image',
					'layout'    => 'col-12 col-md-6',
					'std'       => '',
					'break'     => true,
					'tab'       => 'bank_transfer',
					'condition' => 'payment_bank_transfer_enable:on'
				]
			];
		}
	}
}