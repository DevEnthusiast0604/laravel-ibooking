<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 11/28/2020
 * Time: 9:04 PM
 */
if(!function_exists('get_user_meta')){
    function get_user_meta($userID, $metaKey){
        $meta = DB::table('gmz_meta')
            ->where('post_id', $userID)
            ->where('post_type', 'user')
            ->where('meta_key', $metaKey)
            ->pluck('meta_value')->first();
        return $meta;
    }
}

if(!function_exists('get_admin_user_ids')){
    function get_admin_user_ids(){
        $userIDs = \App\Models\RoleUser::where('role_id', 1)->pluck('user_id')->toArray();
        return $userIDs;
    }
}

if(!function_exists('is_user_login')) {
	function is_user_login() {
		return \Illuminate\Support\Facades\Auth::check();
	}
}

if(!function_exists('get_user_roles')) {
	function get_user_roles( $for_option = false ) {
		$roles = [
			'admin'    => [
				'id'   => 1,
				'name' => __( 'Admin' )
			],
			'partner'  => [
				'id'   => 2,
				'name' => __( 'Partner' )
			],
			'customer' => [
				'id'   => 3,
				'name' => __( 'Customer' )
			]
		];
		if ( $for_option ) {
			$roles_temp = [];
			foreach ( $roles as $key => $val ) {
				$roles_temp[ $val['id'] ] = $val['name'];
			}

			return $roles_temp;
		}

		return $roles;
	}
}

if(!function_exists('get_user_role')) {
	function get_user_role( $user_id = '', $return = 'key' ) {
		if ( empty( $user_id ) ) {
			$user = \Illuminate\Support\Facades\Auth::user();
		} else {
			$user = \App\Models\User::findOrFail( $user_id );
		}

		$role = 'customer';

		if ( $user ) {

			if ( ! $user->roles->isEmpty() ) {
				$role = $user->roles[0]->name;
			}

			if ( $return == 'name' ) {
				$allRoles = get_user_roles();
				if ( isset( $allRoles[ $role ] ) ) {
					$role = $allRoles[ $role ]['name'];
				}
			}

			if ( $return == 'id' ) {
				$allRoles = get_user_roles();
				if ( isset( $allRoles[ $role ] ) ) {
					$role = $allRoles[ $role ]['id'];
				}
			}
		}

		return $role;
	}
}

if(!function_exists('get_current_user_id')) {
	function get_current_user_id() {
		$user = \Illuminate\Support\Facades\Auth::user();
		if ( ! empty( $user ) ) {
			return $user->id;
		}

		return 0;
	}
}

if(!function_exists('get_user_data')) {
	function get_user_data( $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user = \Illuminate\Support\Facades\Auth::user();
		} else {
			$user = \App\Models\User::find( $user_id );
		}
		if ( $user ) {
			return $user->getAttributes();
		}

		return false;
	}
}

if(!function_exists('get_user_name')) {
	function get_user_name( $user_id = '' ) {
		$user_data = get_user_data( $user_id );
		if ( $user_data ) {
			$first_name = $user_data['first_name'];
			$last_name  = $user_data['last_name'];
			if ( ! empty( $last_name ) ) {
				return $first_name . ' ' . $last_name;
			} else {
				return $first_name;
			}
		}

		return '';
	}
}

if(!function_exists('get_user_email')) {
	function get_user_email( $user_id = '' ) {
		$user_data = get_user_data( $user_id );
		if ( $user_data ) {
			return $user_data['email'];
		}

		return false;
	}
}

if(!function_exists('get_user_register_date')) {
	function get_user_register_date( $user_id = '' ) {
		$user_data = get_user_data( $user_id );
		if ( $user_data ) {
			return $user_data['created_at'];
		}

		return false;
	}
}

if(!function_exists('random_user_password')) {
	function random_user_password( $length = 8 ) {
		$alphabet    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()';
		$pass        = array();
		$alphaLength = strlen( $alphabet ) - 1;
		for ( $i = 0; $i < $length; $i ++ ) {
			$n      = rand( 0, $alphaLength );
			$pass[] = $alphabet[ $n ];
		}

		return implode( $pass );
	}
}

if(!function_exists('is_admin')) {
	function is_admin() {
		$role = get_user_role();
		if ( $role == 'admin' ) {
			return true;
		}

		return false;
	}
}

if(!function_exists('is_partner')) {
	function is_partner() {
		$role = get_user_role();
		if ( $role == 'partner' ) {
			return true;
		}

		return false;
	}
}

if(!function_exists('is_customer')) {
	function is_customer() {
		$role = get_user_role();
		if ( $role == 'customer' ) {
			return true;
		}

		return false;
	}
}

if(!function_exists('get_user_avatar')) {
	function get_user_avatar( $user_id = '', $size = 'full' ) {
		$user_data = get_user_data( $user_id );
		if ( $user_data ) {
			$avatar = $user_data['avatar'];
			if ( ! empty( $avatar ) ) {
				$img = get_attachment_url( $avatar, $size );
				if ( ! empty( $img ) ) {
					return $img;
				}
			}
		}

		return placeholder_image( $size );
	}
}

if(!function_exists('is_social_login_enable')) {
	function is_social_login_enable( $provider ) {
		$option = get_option( $provider . '_login_enable', 'on' );
		if ( $option == 'on' ) {
			return true;
		}

		return false;
	}
}

if(!function_exists('get_admin_email')) {
	function get_admin_email() {
		$admin_id = get_option( 'admin_user' );
		if ( ! empty( $admin_id ) ) {
			$email = get_user_email( $admin_id );
			if ( $email ) {
				return $email;
			}
		}

		return false;
	}
}

if(!function_exists('get_user_by')) {
	function get_user_by( $field = 'id', $data ) {
		$user = null;
		switch ( $field ) {
			case 'email':
				$user = \App\Models\User::query()->where( 'email', $data )->first();
				break;
			case 'id':
				$user = \App\Models\User::find( $data );
				break;
		}
		if ( $user ) {
			return $user->getAttributes();
		}

		return false;
	}
}

if(!function_exists('get_users')) {
	function get_users( $data ) {
		$model = new \App\Models\User();
		$data  = $model->getUsers( $data );
		$res   = [];
		if ( ! $data->isEmpty() ) {
			foreach ( $data as $item ) {
				$res[ $item['id'] ] = get_user_name( $item['id'] ) . ' (' . $item['email'] . ')';
			}
		}

		return $res;
	}
}