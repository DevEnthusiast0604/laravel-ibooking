<?php
if(!function_exists('is_enable_review')) {
	function is_enable_review() {
		$option = get_option( 'enable_review', 'on' );
		if ( $option == 'on' ) {
			return true;
		}

		return false;
	}
}

if(!function_exists('is_user_can_review')) {
	function is_user_can_review( $user_id = '', $post_id, $post_type = 'post' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		if ( is_admin() ) {
			return true;
		}

		if ( $post_type !== 'post' ) {
			$enable_review = get_option( 'enable_review', 'on' );
			if ( $enable_review == 'off' ) {
				return false;
			}

			$need_booking = get_option( 'need_booking_to_review', 'off' );
			if ( $need_booking == 'off' ) {
				return true;
			}

			$orderModel  = new \App\Models\Order();
			$has_booking = $orderModel->query()
			                          ->where( 'buyer', $user_id )
			                          ->where( 'post_id', $post_id )
			                          ->where( 'post_type', $post_type )
			                          ->whereIn( 'status', [ 'incomplete', 'completed' ] )
			                          ->get()->first();

			if ( ! empty( $has_booking ) ) {
				return true;
			}

			return false;
		} else {
			$enable_review = get_option( 'enable_post_review', 'on' );
			if ( $enable_review == 'off' ) {
				return false;
			}

			return true;
		}
	}
}

if(!function_exists('is_need_approve_review')) {
	function is_need_approve_review() {
		$option = get_option( 'need_approve_review', 'off' );
		if ( $option == 'on' ) {
			return true;
		}

		return false;
	}
}

if(!function_exists('get_comment_list')) {
	function get_comment_list( $post_id, $data ) {
		$comment = new \App\Models\Comment();

		return $comment->getCommentByPostID( $post_id, $data );
	}
}

if(!function_exists('get_comment_per_page')) {
	function get_comment_per_page() {
		return 5;
	}
}

if(!function_exists('get_comment_number')) {
	function get_comment_number( $post_id, $type = 'posts' ) {
		$comment       = new \App\Models\Comment();
		$comment_count = $comment->getCommentCountByPostID( $post_id, $type );

		return $comment_count;
	}
}

if(!function_exists('render_list_comment')) {
	function render_list_comment( $comments, $depth = 0, $sub = false ) {
		if ( $sub ) {
			echo '<ul class="comment-child clearfix">';
		} else {
			echo '<ul class="comment-list">';
		}
		foreach ( $comments as $k => $v ) {
			?>
            <li id="comment-<?php echo esc_attr( $v->comment_id ); ?>"
                class="comment comment-home odd alt thread-odd thread-alt depth-1">
                <div id="div-comment-<?php echo esc_attr( $v->comment_id ) ?>" class="article comment  clearfix"
                     inline_comment="comment">
                    <div class="comment-item-head">
                        <div class="media">
                            <div class="media-left">
                                <img alt="" src="<?php echo get_user_avatar( $v->comment_author ) ?>"
                                     class="avatar avatar-50 photo avatar-default" height="50" width="50">
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
									<?php
									if ( is_user_login() ) {
										echo esc_html( get_user_name( $v->comment_author ) );
									} else {
										echo esc_html( $v->comment_name );
									}
									?>
                                </h4>
                                <div class="date"><?php echo esc_html( date( get_date_format(), strtotime( $v->created_at ) ) ) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="comment-item-body">
                        <div class="comment-content">
                            <p class="comment-title"><?php echo esc_html( $v->comment_title ); ?></p>
							<?php
							if ( in_array( $v->post_type, [
								GMZ_SERVICE_CAR,
								GMZ_SERVICE_APARTMENT,
								GMZ_SERVICE_HOTEL,
								GMZ_SERVICE_SPACE,
								GMZ_SERVICE_TOUR,
                                GMZ_SERVICE_BEAUTY
							] ) ) {
								review_rating_star( $v->comment_rate );
							}
							?>
                            <p><?php echo esc_html( $v->comment_content ); ?></p>
                        </div>
                    </div>
                </div>
				<?php
				if ( $v->post_type == 'post' ) {
					?>
					<?php if ( $depth < 2 ) { ?>
                        <div class="reply-box-wrapper" id="reply-box"
                             data-comment_id="<?php echo esc_attr( $v->comment_id ) ?>">
                            <a href="javascript:void();"
                               class="btn btn-primary btn-sm btn-reply"><?php echo __( 'Reply' ) ?></a>
                            <a href="javascript:void();"
                               class="btn btn-primary btn-sm btn-cancel-reply"><?php echo __( 'Cancel' ) ?></a>
                            <div class="reply-form"></div>
                        </div>
						<?php
					}
					$child_comments = get_comment_list( $v->post_id, [
						'parent' => $v->comment_id
					] );
					if ( ! $child_comments->isEmpty() ) {
						$depth ++;
						render_list_comment( $child_comments, $depth, true );
					}
					?>
					<?php
				}
				?>
            </li>
			<?php
		}
		echo '</ul>';
	}
}

if(!function_exists('review_rating_star')) {
	function review_rating_star( $rate ) {
		if ( ! empty( $rate ) ) {
			echo '<div class="star-rating">';
			for ( $i = 1; $i <= 5; $i ++ ) {
				if ( $i <= $rate ) {
					echo '<i class="fa fa-star"></i>';
				} else {
					echo '<i class="fa fa-star star-none"></i>';
				}
			}
			echo '</div>';
		}
	}
}
