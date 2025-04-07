<?php
if(!function_exists('get_room_price')) {
	function get_room_price( $room, $check_in, $check_out ) {
		$room_id     = $room['id'];
		$base_price  = $room['base_price'];
		$check_in    = strtotime( $check_in );
		$check_out   = strtotime( $check_out );
		$number_days = gmz_date_diff( $check_in, $check_out );

		$avail_model = new \App\Models\RoomAvailability();
		$data        = $avail_model->query()->where( 'post_id', $room_id )
		                           ->where( 'check_in', '>=', $check_in )
		                           ->where( 'check_in', '<', $check_out )
		                           ->get();

		$total_price = 0;
		$remain_days = $number_days;
		if ( ! empty( $data ) ) {
			foreach ( $data as $k => $v ) {
				if ( ! empty( $v['price'] ) ) {
					$total_price += $v['price'];
				} else {
					$total_price += $base_price;
				}
				$remain_days --;
			}
		}

		$total_price += $remain_days * $base_price;

		return $total_price;
	}
}