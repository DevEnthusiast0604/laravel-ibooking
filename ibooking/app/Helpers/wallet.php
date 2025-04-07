<?php
/* add money to your wallet */
if(!function_exists('add_money_to_wallet')) {
	function add_money_to_wallet( $order_id ) {
		$model      = new \App\Models\Earnings();
		$modelOrder = new \App\Models\Order();
		$order      = $modelOrder->query()->findOrFail( $order_id );
		if ( $order['money_to_wallet'] == 0 ):
			$wallet     = $model->query()->where( [ 'user_id' => $order['owner'] ] )->first();
			$wallet_new = array();
			if ( empty( $wallet ) ) {
				$wallet = $model->query()->create( [
					'user_id'      => $order['owner'],
					'total'        => 0,
					'balance'      => 0,
					'net_earnings' => 0,
				] );
			}
			$wallet_new['total']        = $wallet['total'] + $order['total'];
			$wallet_new['balance']      = $wallet['balance'] + ( $order['total'] * ( ( 100 - $order['commission'] ) / 100 ) );
			$wallet_new['net_earnings'] = $wallet['net_earnings'] + ( $order['total'] * ( ( 100 - $order['commission'] ) / 100 ) );

			$response = $model->query()->whereKey( $wallet['id'] )->update( $wallet_new );
			if ( $response ) {
				$modelOrder->query()->whereKey( $order_id )->update( [ 'money_to_wallet' => 1 ] );
				$log = 'Add $' . $order['total'] . ' to the wallet';
				$modelOrder->appendChangeLog( $order_id, 'system', $log );
			}
		endif;
	}
}

/* subtract money from wallet */
if(!function_exists('subtract_money_form_wallet')) {
	function subtract_money_form_wallet( $order_id ) {
		$model      = new \App\Models\Earnings();
		$modelOrder = new \App\Models\Order();

		$order = $modelOrder->query()->findOrFail( $order_id );
		if ( $order['money_to_wallet'] == 1 ):
			$wallet     = $model->query()->where( [ 'user_id' => $order['owner'] ] )->first();
			$wallet_new = array();

			$wallet_new['total']        = $wallet['total'] - $order['total'];
			$wallet_new['balance']      = $wallet['balance'] - ( $order['total'] * ( ( 100 - $order['commission'] ) / 100 ) );
			$wallet_new['net_earnings'] = $wallet['net_earnings'] - ( $order['total'] * ( ( 100 - $order['commission'] ) / 100 ) );

			$response = $model->query()->whereKey( $wallet['id'] )->update( $wallet_new );
			if ( $response ) {
				$modelOrder->query()->whereKey( $order_id )->update( [ 'money_to_wallet' => 0 ] );
				$log = 'Subtract $' . $order['total'] . ' from the wallet';
				$modelOrder->appendChangeLog( $order_id, 'system', $log );
			}
		endif;
	}
}

if(!function_exists('get_money_on_hold')) {
	function get_money_on_hold( $user_id = "" ) {

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$model            = new \App\Models\Order();
		$present_time     = strtotime( "+1 days", TIME() );//1 day after end_date
		$status_complete  = GMZ_STATUS_COMPLETE;
		$status_cancelled = GMZ_STATUS_CANCELLED;
		$status_payment   = GMZ_PAYMENT_COMPLETED;

		$query = $model->query();
		$query->select( 'id' );
		$query->selectRaw( "SUM((total - (total * commission)/100)) as total_net_earnings" );
		if ( $user_id != - 1 ) {
			$query->where( 'owner', $user_id );
		}
		$query->whereRaw( "(status = '{$status_complete}' AND end_date > '{$present_time}') OR (status = '{$status_cancelled}' AND payment_status = '{$status_payment}')" );
		$on_hold = $query->first();

		return $on_hold["total_net_earnings"];
	}
}

if(!function_exists('get_withdrawal_status')) {
	function get_withdrawal_status( $key = null ) {
		switch ( $key ) {
			case GMZ_STATUS_CANCELLED :
				$text    = __( 'Cancelled' );
				$classes = 'badge badge-danger';
				break;
			case GMZ_STATUS_ACCEPT :
				$text    = __( 'Accept' );
				$classes = 'badge badge-success';
				break;
			case GMZ_STATUS_PENDING :
				$text    = __( 'Pending' );
				$classes = 'badge badge-warning';
				break;
			default :
				$text    = $key;
				$classes = 'badge badge-info';
		}

		return sprintf( '<span class="%1$s">%2$s</span>', $classes, $text );
	}
}

if(!function_exists('list_withdrawal_status')) {
	function list_withdrawal_status() {
		return [
			GMZ_STATUS_CANCELLED => __( 'Cancel' ),
			GMZ_STATUS_ACCEPT    => __( 'Accept' ),
			GMZ_STATUS_PENDING   => __( 'Pending' )
		];
	}
}

if(!function_exists('is_withdrawal_status')) {
	function is_withdrawal_status( $status ) {
		$arr = list_withdrawal_status();
		if ( array_key_exists( $status, $arr ) ) {
			return true;
		} else {
			return false;
		}
	}
}