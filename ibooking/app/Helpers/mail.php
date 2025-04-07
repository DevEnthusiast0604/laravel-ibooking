<?php
if(!function_exists('send_email')) {
	function send_email( $to, $subject, $body, $from = '', $from_label = '', $reply = '' ) {
		try {
			\Illuminate\Support\Facades\Mail::send( [], [], function ( $message ) use ( $from, $from_label, $to, $subject, $body, $reply ) {
				if ( empty( $from ) ) {
					$from = get_option( 'email_username');
				}
				if ( empty( $from_label ) ) {
					$from_label = get_translate( get_option( 'site_name', 'iBooking' ) );
				}
				$message->from( $from, $from_label );
				$message->subject( $subject );
				$message->to( $to );
				$message->setBody( $body, 'text/html' );
				if ( ! empty( $reply ) ) {
					$message->replyTo( $reply );
				}
			} );

			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
}
