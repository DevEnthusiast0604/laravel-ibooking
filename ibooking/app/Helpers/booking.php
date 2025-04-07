<?php

use Carbon\Carbon;
use Carbon\CarbonPeriod;

if(!function_exists('the_order_status')) {
	function the_order_status( $key = '' ) {
		switch ( $key ) {
			case GMZ_STATUS_INCOMPLETE :
				$text    = __( 'Incomplete' );
				$classes = 'badge badge-info';
				break;
			case GMZ_STATUS_COMPLETE :
				$text    = __( 'Completed' );
				$classes = 'badge badge-success';
				break;
			case GMZ_STATUS_CANCELLED :
				$text    = __( 'Cancelled' );
				$classes = 'badge badge-danger';
				break;
			case GMZ_STATUS_REFUNDED :
				$text    = __( 'Refunded' );
				$classes = 'badge badge-warning';
				break;
			default :
				$text    = $key;
				$classes = 'badge badge-info';
		}

		return sprintf( '<span class="%1$s">%2$s</span>', $classes, $text );
	}
}

if(!function_exists('list_order_status')) {
	function list_order_status($status = '') {
	    switch ($status){
            case GMZ_STATUS_COMPLETE:
                return [
                    GMZ_STATUS_CANCELLED  => __( 'Cancel order' ),
                    GMZ_STATUS_REFUNDED   => __( 'Refunded' ),
                    GMZ_STATUS_INCOMPLETE => __( 'Incomplete' )
                ];
                break;
            case GMZ_STATUS_INCOMPLETE:
                return [
                    GMZ_STATUS_CANCELLED  => __( 'Cancel order' ),
                    GMZ_STATUS_COMPLETE   => __( 'Complete' )
                ];
                break;
            case GMZ_STATUS_CANCELLED:
                return [
                    GMZ_STATUS_REFUNDED   => __( 'Refunded' ),
                    GMZ_STATUS_COMPLETE   => __( 'Complete' )
                ];
                break;
            case GMZ_STATUS_REFUNDED:
                return [
                    GMZ_STATUS_COMPLETE   => __( 'Complete' )
                ];
                break;
            default:
                return [
                    GMZ_STATUS_CANCELLED  => __( 'Cancel order' ),
                    GMZ_STATUS_REFUNDED   => __( 'Refunded' ),
                    GMZ_STATUS_COMPLETE   => __( 'Complete' ),
                    GMZ_STATUS_INCOMPLETE => __( 'Incomplete' )
                ];
                break;
        }
	}
}

if(!function_exists('is_order_status')) {
	function is_order_status( $status ) {
		$arr = list_order_status();
		if ( array_key_exists( $status, $arr ) ) {
			return true;
		} else {
			return false;
		}
	}
}

if(!function_exists('get_processing_log')) {
	function get_processing_log( $string ) {
		if ( empty( $string ) ) {
			return null;
		}
		$log = rtrim( $string, "," );
		$log = "[" . $log . "]";

		return json_decode( $log, true );
	}
}

if(!function_exists('get_tax')) {
	function get_tax() {
		$tax_included = get_option( 'tax_included' );
		$tax_percent  = get_option( 'tax_percent' );

		return [
			'included' => $tax_included,
			'percent'  => floatval( $tax_percent )
		];
	}
}

if(!function_exists('get_payment_type')) {
	function get_payment_type( $payment_type ) {
		$gateway = Gateway::inst()->getGateway( $payment_type );
		if ( $gateway ) {
			return $gateway->getName();
		}

		return ucwords( str_replace( '_', ' ', $payment_type ) );
	}
}

if(!function_exists('the_paid')) {
	function the_paid( $payment_status ) {
		return ( empty( $payment_status ) ) ? '<span class="text-warning">' . __( 'Unpaid' ) . '</span>' : '<span class="text-danger">' . __( 'Paid' ) . '</span>';
	}
}

if(!function_exists('get_list_date_form_today')) {
	function get_list_date_form_today( $subDays ) {
		$dt        = Carbon::now();
		$startDate = $dt->today()->subDays( $subDays )->toDateString();
		$endDate   = $dt->today()->toDateString();
		//get list period
		$period = CarbonPeriod::create( $startDate, $endDate );
		//format date
		$dates = array();
		foreach ( $period as $date ) {
			$dates[] = $date->toDateString();
		}

		return $dates;
	}
}