<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/18/2020
 * Time: 11:15 PM
 */
if(!function_exists('get_attachment')) {
	function get_attachment( $attachment_id ) {
        return \App\Repositories\MediaRepository::inst()->find($attachment_id);
	}
}

if(!function_exists('get_attachment_info')) {
	function get_attachment_info( $attachment_id, $size = 'full', $default = true ) {
		$attachment = get_attachment( $attachment_id );
		if ( $attachment ) {
			return [
				'id'          => $attachment->id,
				'url'         => get_attachment_url( $attachment, $size, $default ),
				'description' => $attachment->media_description,
				'author'      => $attachment->author,
				'type'        => $attachment->media_type,
			];
		}

		return null;
	}
}

if(!function_exists('get_attachment_url')) {
	function get_attachment_url( $attachment_id, $size = 'full', $default = true ) {
		if ( is_object( $attachment_id ) ) {
			$attachment = $attachment_id;
		} else {
			$attachment = get_attachment( $attachment_id );
		}

		if ( $attachment ) {
			$url = $attachment->media_url;
			if ( \App::environment( 'production_ssl' ) ) {
				$url = str_replace( 'http:', 'https:', $url );
			}
			if ( $size == 'full' ) {
				return $url;
			}
			$media_url = $url;
			$url_info  = pathinfo( $media_url );
			$url       = $url_info['dirname'];

			$media_path = $attachment->media_path;
			$path_info  = pathinfo( $media_path );
			$name       = $path_info['filename'];
			$extension  = $path_info['extension'];
			$path       = $path_info['dirname'];

			switch ( $size ) {
				case 'medium':
					$file = $path . '/' . $name . '-800x600' . '.' . $extension;
					break;
				case 'small':
					$file = $path . '/' . $name . '-400x300' . '.' . $extension;
					break;
				default:
					$file = $path . '/' . $name . '-' . $size[0] . 'x' . $size[1] . '.' . $extension;
					break;
			}
			if ( file_exists( $file ) ) {
				return $url . '/' . basename( $file );
			} else {
				if ( file_exists( $media_path ) ) {
					if ( function_exists( 'exif_imagetype' ) ) {
						$detectedType = exif_imagetype( $media_path );
					} else {
						$r            = getimagesize( $media_path );
						$detectedType = $r[2];
					}

					if ( $detectedType ) {
						crop_image( $media_path, $size );
						if ( is_file( $file ) ) {
							return $url . '/' . basename( $file );
						} else {
							return placeholder_image( $size );
						}
					} else {
						return placeholder_image( $size );
					}
				} else {
					return placeholder_image( $size );
				}
			}
		}

		if ( $default ) {
			return placeholder_image( $size );
		}

		return '';
	}
}

if(!function_exists('placeholder_image')) {
	function placeholder_image( $size = 'full' ) {
		switch ( $size ) {
			case 'full':
				$url = '//via.placeholder.com/1200x900';
				break;
			case 'medium':
				$url = '//via.placeholder.com/800x600';
				break;
			case 'small':
				$url = '//via.placeholder.com/400x300';
				break;
			default:
				$url = '//via.placeholder.com/' . $size[0] . 'x' . $size[1];
				break;
		}

		return $url;
	}
}

if(!function_exists('crop_image')) {
	function crop_image( $path, $size = [ 150, 150 ] ) {
		switch ( $size ) {
			case 'full':
				$size = [ 1200, 900 ];
				break;
			case 'medium':
				$size = [ 800, 600 ];
				break;
			case 'small':
				$size = [ 400, 300 ];
				break;
		}
		try {
			$image = new \Gumlet\ImageResize( $path );
			$image->crop( $size[0], $size[1] );
			$path_info = pathinfo( $path );
			$name      = $path_info['filename'] . '-' . $size[0] . 'x' . $size[1];
			$newpath   = $path_info['dirname'] . '/' . $name . '.' . $path_info['extension'];
			$image->save( $newpath );

			return $newpath;
		} catch ( Exception $ex ) {
			return false;
		}

	}
}

if(!function_exists('get_attachment_alt')) {
	function get_attachment_alt( $attachment_id ) {
		$attachment = get_attachment( $attachment_id );
		if ( $attachment ) {
			return esc_attr( $attachment->media_description );
		}

		return '';
	}
}

if(!function_exists('get_video_url')) {
	function get_video_url( $video_url ) {
		if ( strpos( $video_url, 'youtube' ) !== false || strpos( $video_url, 'youtu.be' ) !== false ) {
			preg_match( "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $video_url, $matches );
			$id = $matches[1];

			return '//www.youtube.com/watch?v=' . $id;
		} elseif ( strpos( $video_url, 'vimeo' ) !== false ) {
			$id = (int) substr( parse_url( $video_url, PHP_URL_PATH ), 1 );

			return '//vimeo.com/' . $id;
		}

		return $video_url;
	}
}