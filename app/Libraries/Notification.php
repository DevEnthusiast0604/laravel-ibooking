<?php
use Illuminate\Support\Facades\Session;

if(!class_exists('GMZ_Notification')) {
	class GMZ_Notification {
		private static $_inst;

		public function addNew( $from, $to, $title = '', $message = '', $type = 'global' ) {
			if ( empty( $from ) ) {
				$from = get_current_user_id();
			}
			$model = new \App\Models\Notification();
			$data  = [
				'user_from'  => $from,
				'user_to'    => $to,
				'title'      => $title,
				'message'    => $message,
				'type'       => $type,
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now()
			];

			return $model->insertNotification( $data );
		}

		public function deleteNotification( $id ) {
			$model = new \App\Models\Notification();

			return $model->deleteNotification( $id );
		}

		public function getLatestNotificationByUser( $user_id, $type = 'to' ) {
			$model = new \App\Models\Notification();

			return $model->getLatestNotificationByUser( $user_id, $type );
		}

		public static function inst() {
			if ( empty( self::$_inst ) ) {
				self::$_inst = new self();
			}

			return self::$_inst;
		}
	}
}