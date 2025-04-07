<?php
if(!function_exists('body_class')){
    function body_class(){
        $class = apply_filter('gmz_body_class', '');
        return $class;
    }
}

if(!function_exists('get_term_link')) {
    function get_term_link(){
        $term_link = 'javascript:void(0);';
        $term_page = get_option('page_term_conditional');
        if($term_page){
            $page = get_post($term_page, 'page');
            if($page){
                $term_link = get_page_permalink($page['post_slug']);
            }
        }
        return $term_link;
    }
}
if(!function_exists('glob_recursive')) {
	function glob_recursive( $pattern, $flags = 0 ) {
		$files = glob( $pattern, $flags );
		foreach ( glob( dirname( $pattern ) . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $dir ) {
			$files = array_merge( $files, glob_recursive( $dir . '/' . basename( $pattern ), $flags ) );
		}

		return $files;
	}
}

if(!function_exists('rmdir_recursive')) {
	function rmdir_recursive( $dir ) {
		if ( is_dir( $dir ) ) {
			$objects = scandir( $dir );
			foreach ( $objects as $object ) {
				if ( $object != "." && $object != ".." ) {
					if ( is_dir( $dir . DIRECTORY_SEPARATOR . $object ) && ! is_link( $dir . "/" . $object ) ) {
						rmdir_recursive( $dir . DIRECTORY_SEPARATOR . $object );
					} else {
						if ( is_file( $dir . DIRECTORY_SEPARATOR . $object ) ) {
							unlink( $dir . DIRECTORY_SEPARATOR . $object );
						} elseif ( is_dir( $dir . DIRECTORY_SEPARATOR . $object ) ) {
							rmdir( $dir );
						}
					}
				}
			}
			rmdir( $dir );
		}
	}
}

if(!function_exists('admin_enqueue_styles')) {
	function admin_enqueue_styles( $styles ) {
		\App\Modules\Backend\Controllers\ScriptController::inst()->setQueueStyle( $styles );
	}
}

if(!function_exists('admin_enqueue_scripts')) {
	function admin_enqueue_scripts( $scripts ) {
		\App\Modules\Backend\Controllers\ScriptController::inst()->setQueueScript( $scripts );
	}
}

if(!function_exists('enqueue_styles')) {
	function enqueue_styles( $styles, $module = '') {
	    if(empty($module)) {
            \App\Modules\Frontend\Controllers\ScriptController::inst()->setQueueStyle($styles);
        }else{
            $module = '\\App\\Themes\\' . ucfirst($module) . '\\Controllers\\ScriptController';
            $module::inst()->setQueueStyle($styles);
        }
	}
}

if(!function_exists('enqueue_scripts')) {
	function enqueue_scripts( $scripts, $module = '') {
        if(empty($module)) {
            \App\Modules\Frontend\Controllers\ScriptController::inst()->setQueueScript( $scripts );
        }else{
            $module = '\\App\\Themes\\' . ucfirst($module) . '\\Controllers\\ScriptController';
            $module::inst()->setQueueScript($scripts);
        }

	}
}

if(!function_exists('admin_init_header')) {
	function admin_init_header() {
		\App\Modules\Backend\Controllers\ScriptController::inst()->initAdminHeader();
	}
}

if(!function_exists('admin_init_footer')) {
	function admin_init_footer() {
		\App\Modules\Backend\Controllers\ScriptController::inst()->initAdminFooter();
	}
}

if(!function_exists('init_header')) {
	function init_header($module = '') {
	    if(empty($module)){
            \App\Modules\Frontend\Controllers\ScriptController::inst()->initHeader();
        }else{
            $module = '\\App\\Themes\\' . ucfirst($module) . '\\Controllers\\ScriptController';
            $module::inst()->initHeader();
        }
	}
}

if(!function_exists('init_footer')) {
	function init_footer($module = '') {
        if(empty($module)){
            \App\Modules\Frontend\Controllers\ScriptController::inst()->initFooter();
        }else{
            $module = '\\App\\Themes\\' . ucfirst($module) . '\\Controllers\\ScriptController';
            $module::inst()->initFooter();
        }
	}
}

if(!function_exists('gmz_hashing')) {
	function gmz_hashing( $value ) {
		$key = admin_config( 'key_hashing' );

		return md5( $key . $value );
	}
}

if(!function_exists('gmz_compare_hashing')) {
	function gmz_compare_hashing( $need, $hashed ) {
		$hashing = gmz_hashing( $need );
		if ( $hashing === $hashed ) {
			return true;
		}

		return false;
	}
}

if(!function_exists('get_config_posttype')) {
	function get_config_posttype( $type = 'post' ) {
		$post_types = admin_config( 'post_types' );
		if ( ! empty( $post_types ) && isset( $post_types[ $type ] ) ) {
			return $post_types[ $type ];
		}

		return $post_types;
	}
}

if(!function_exists('unset_data')) {
	function unset_data( $data, $unset_items = [] ) {
		if ( empty( $unset_items ) ) {
			$unset_items = [ 'post_id', '_token', 'finish' ];
		}

		foreach ( $unset_items as $item ) {
			if ( isset( $data[ $item ] ) ) {
				unset( $data[ $item ] );
			}
		}

		return $data;
	}
}

if(!function_exists('esc_html')) {
	function esc_html( $text ) {
		$text      = trim( $text );
		$safe_text = _check_invalid_utf8( $text );
		$safe_text = _specialchars( $safe_text, ENT_QUOTES );

		return $safe_text;
	}
}

if(!function_exists('esc_attr')) {
	function esc_attr( $text ) {
		$safe_text = _check_invalid_utf8( $text );
		$safe_text = _specialchars( $safe_text, ENT_QUOTES );

		return $safe_text;
	}
}

if(!function_exists('esc_sql')) {
	function esc_sql( $text ) {
		return str_replace( array( '\\', "\0", "\n", "\r", "'", '"', "\x1a" ), array(
			'\\\\',
			'\\0',
			'\\n',
			'\\r',
			"\\'",
			'\\"',
			'\\Z'
		), $text );
	}
}

if(!function_exists('esc_url')) {
	function esc_url( $url ) {
		return str_replace( ' ', '%20', $url );
	}
}

if(!function_exists('_specialchars')) {
	function _specialchars( $string, $quote_style = ENT_NOQUOTES, $charset = false, $double_encode = false ) {
		$string = (string) $string;

		if ( 0 === strlen( $string ) ) {
			return '';
		}

		// Don't bother if there are no specialchars - saves some processing
		if ( ! preg_match( '/[&<>"\']/', $string ) ) {
			return $string;
		}

		// Account for the previous behaviour of the function when the $quote_style is not an accepted value
		if ( empty( $quote_style ) ) {
			$quote_style = ENT_NOQUOTES;
		} elseif ( ! in_array( $quote_style, array( 0, 2, 3, 'single', 'double' ), true ) ) {
			$quote_style = ENT_QUOTES;
		}

		// Store the site charset as a static to avoid multiple calls to wp_load_alloptions()
		if ( ! $charset ) {
			static $_charset = null;
			if ( ! isset( $_charset ) ) {
				$alloptions = [];
				$_charset   = isset( $alloptions['blog_charset'] ) ? $alloptions['blog_charset'] : '';
			}
			$charset = $_charset;
		}

		if ( in_array( $charset, array( 'utf8', 'utf-8', 'UTF8' ) ) ) {
			$charset = 'UTF-8';
		}

		$_quote_style = $quote_style;

		if ( $quote_style === 'double' ) {
			$quote_style  = ENT_COMPAT;
			$_quote_style = ENT_COMPAT;
		} elseif ( $quote_style === 'single' ) {
			$quote_style = ENT_NOQUOTES;
		}

		if ( ! $double_encode ) {
			// Guarantee every &entity; is valid, convert &garbage; into &amp;garbage;
			// This is required for PHP < 5.4.0 because ENT_HTML401 flag is unavailable.
			$string = kses_normalize_entities( $string );
		}

		$string = @htmlspecialchars( $string, $quote_style, $charset, $double_encode );

		// Back-compat.
		if ( 'single' === $_quote_style ) {
			$string = str_replace( "'", '&#039;', $string );
		}

		return $string;
	}
}

if(!function_exists('kses_normalize_entities')) {
	function kses_normalize_entities( $string ) {
		// Disarm all entities by converting & to &amp;
		$string = str_replace( '&', '&amp;', $string );

		// Change back the allowed entities in our entity whitelist
		$string = preg_replace_callback( '/&amp;([A-Za-z]{2,8}[0-9]{0,2});/', 'kses_named_entities', $string );
		$string = preg_replace_callback( '/&amp;#(0*[0-9]{1,7});/', 'kses_normalize_entities2', $string );
		$string = preg_replace_callback( '/&amp;#[Xx](0*[0-9A-Fa-f]{1,6});/', 'kses_normalize_entities3', $string );

		return $string;
	}
}

if(!function_exists('kses_named_entities')) {
	function kses_named_entities( $matches ) {
		global $allowedentitynames;

		if ( empty( $matches[1] ) && is_array( $matches[1] ) ) {
			return '';
		}

		$i = $matches[1];

		if ( is_array( $allowedentitynames ) ) {
			return ( ! in_array( $i, $allowedentitynames ) ) ? "&amp;$i;" : "&$i;";
		} else {
			return '';
		}
	}
}

if(!function_exists('kses_normalize_entities2')) {
	function kses_normalize_entities2( $matches ) {
		if ( empty( $matches[1] ) ) {
			return '';
		}

		$i = $matches[1];
		if ( valid_unicode( $i ) ) {
			$i = str_pad( ltrim( $i, '0' ), 3, '0', STR_PAD_LEFT );
			$i = "&#$i;";
		} else {
			$i = "&amp;#$i;";
		}

		return $i;
	}
}

if(!function_exists('kses_normalize_entities3')) {
	function kses_normalize_entities3( $matches ) {
		if ( empty( $matches[1] ) ) {
			return '';
		}

		$hexchars = $matches[1];

		return ( ! valid_unicode( hexdec( $hexchars ) ) ) ? "&amp;#x$hexchars;" : '&#x' . ltrim( $hexchars, '0' ) . ';';
	}
}

if(!function_exists('bd_enqueue_styles')) {
	function bd_enqueue_styles( $styles ) {
		return \App\Modules\Frontend\Controllers\ScriptController::inst()->setBDQueueStyle( $styles );
	}
}

if(!function_exists('bd_enqueue_scripts')) {
	function bd_enqueue_scripts( $scripts ) {
		return \App\Modules\Frontend\Controllers\ScriptController::inst()->setBDQueueScript( $scripts );
	}
}

if(!function_exists('valid_unicode')) {
	function valid_unicode( $i ) {
		return ( $i == 0x9 || $i == 0xa || $i == 0xd ||
		         ( $i >= 0x20 && $i <= 0xd7ff ) ||
		         ( $i >= 0xe000 && $i <= 0xfffd ) ||
		         ( $i >= 0x10000 && $i <= 0x10ffff ) );
	}
}

if(!function_exists('_check_invalid_utf8')) {
	function _check_invalid_utf8( $string, $strip = false ) {
		$string = (string) $string;

		if ( 0 === strlen( $string ) ) {
			return '';
		}

		// Store the site charset as a static to avoid multiple calls to get_option()
		static $is_utf8 = null;
		if ( ! isset( $is_utf8 ) ) {
			$is_utf8 = in_array( 'utf-8', array( 'utf8', 'utf-8', 'UTF8', 'UTF-8' ) );
		}
		if ( ! $is_utf8 ) {
			return $string;
		}

		// Check for support for utf8 in the installed PCRE library once and store the result in a static
		static $utf8_pcre = null;
		if ( ! isset( $utf8_pcre ) ) {
			$utf8_pcre = @preg_match( '/^./u', 'a' );
		}
		// We can't demand utf8 in the PCRE installation, so just return the string in those cases
		if ( ! $utf8_pcre ) {
			return $string;
		}

		// preg_match fails when it encounters invalid UTF8 in $string
		if ( 1 === @preg_match( '/^./us', $string ) ) {
			return $string;
		}

		// Attempt to strip the bad chars if requested (not recommended)
		if ( $strip && function_exists( 'iconv' ) ) {
			return iconv( 'utf-8', 'utf-8', $string );
		}

		return '';
	}
}

if(!function_exists('maybe_unserialize')) {
	function maybe_unserialize( $original ) {
		if ( is_serialized( $original ) ) {
			return @unserialize( $original );
		}
		if ( $original == '[]' ) {
			$original = [];
		}

		return $original;
	}
}

if(!function_exists('is_serialized')) {
	function is_serialized( $data, $strict = true ) {
		// If it isn't a string, it isn't serialized.
		if ( ! is_string( $data ) ) {
			return false;
		}
		$data = trim( $data );
		if ( 'N;' == $data ) {
			return true;
		}
		if ( strlen( $data ) < 4 ) {
			return false;
		}
		if ( ':' !== $data[1] ) {
			return false;
		}
		if ( $strict ) {
			$lastc = substr( $data, - 1 );
			if ( ';' !== $lastc && '}' !== $lastc ) {
				return false;
			}
		} else {
			$semicolon = strpos( $data, ';' );
			$brace     = strpos( $data, '}' );
			// Either ; or } must exist.
			if ( false === $semicolon && false === $brace ) {
				return false;
			}
			// But neither must be in the first X characters.
			if ( false !== $semicolon && $semicolon < 3 ) {
				return false;
			}
			if ( false !== $brace && $brace < 4 ) {
				return false;
			}
		}
		$token = $data[0];
		switch ( $token ) {
			case 's':
				if ( $strict ) {
					if ( '"' !== substr( $data, - 2, 1 ) ) {
						return false;
					}
				} elseif ( false === strpos( $data, '"' ) ) {
					return false;
				}
			// Or else fall through.
			case 'a':
			case 'O':
				return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
			case 'b':
			case 'i':
			case 'd':
				$end = $strict ? '$' : '';

				return (bool) preg_match( "/^{$token}:[0-9.E+-]+;$end/", $data );
		}

		return false;
	}
}

if(!function_exists('get_icon')) {
	function get_icon( $name = '', $color = '', $width = '', $height = '', $stroke = false ) {
		global $gmz_fonts;
		if ( ! $gmz_fonts ) {
			include public_path( 'fonts/fonts.php' );
			if ( isset( $fonts ) ) {
				$gmz_fonts = $fonts;
			}
			include public_path( 'fonts/system-fonts.php' );
			if ( isset( $system_fonts ) ) {
				$gmz_fonts = array_merge( $gmz_fonts, $system_fonts );
			}
		}
		if ( empty( $gmz_fonts ) ) {
			return '';
		}
		if ( ! isset( $gmz_fonts[ $name ] ) ) {
			return '';
		}
		$icon = $gmz_fonts[ $name ];
		if ( ! empty( $color ) ) {
			if ( $stroke ) {
				$icon = preg_replace( '/stroke="(.{7})"/', 'stroke="' . $color . '"', $icon );
				$icon = preg_replace( '/stroke:(.{7})/', 'stroke:' . $color, $icon );
			} else {
				$icon = preg_replace( '/fill="(.{7})"/', 'fill="' . $color . '"', $icon );
				$icon = preg_replace( '/fill:(.{7})/', 'fill:' . $color, $icon );
			}
		}

		if ( ! empty( $width ) ) {
			$icon = preg_replace( '/width="(\d{2}[a-z]{2})"/', 'width="' . $width . '"', $icon );
		}

		if ( ! empty( $height ) ) {
			$icon = preg_replace( '/height="(\d{2}[a-z]{2})"/', 'height="' . $height . '"', $icon );
		}

		return '<i class="gmz-icon">' . $icon . '</i>';
	}
}

if(!function_exists('_n')) {
	function _n( $single = '%s', $multi = '%s', $var = 0 ) {
		if ( $var > 1 || $var == 0 ) {
			return str_replace( '%s', $var, $multi );
		} else {
			return str_replace( '%s', $var, $single );
		}
	}
}

if(!function_exists('dashboard_url')) {
	function dashboard_url( $screen = '' ) {
		$prefix = admin_config( 'prefix' );

		return url( $prefix . '/' . $screen );
	}
}

if(!function_exists('admin_config')) {
	function admin_config( $key = '', $file = 'backend' ) {
		if ( ! empty( $key ) ) {
			return config( $file . '.' . $key );
		} else {
			return config( $file );
		}
	}
}

if(!function_exists('frontend_config')) {
	function frontend_config( $key = '', $file = 'frontend' ) {
		if ( ! empty( $key ) ) {
			return config( $file . '.' . $key );
		} else {
			return config( $file );
		}
	}
}

if(!function_exists('d')) {
	function d( $arr ) {
		echo '<pre style="background: #000; padding: 20px; color: #fff;">';
		print_r( $arr );
		echo '</pre>';
	}
}

if(!function_exists('gmz_parse_args')) {
	function gmz_parse_args( $args, $defaults = '' ) {
		if ( is_object( $args ) ) {
			$r = get_object_vars( $args );
		} elseif ( is_array( $args ) ) {
			$r =& $args;
		} else {
			parse_str( $args, $r );
		}

		if ( is_array( $defaults ) ) {
			foreach ( $defaults as $key => $value ) {
				if ( isset( $r[ $key ] ) && ! empty( $r[ $key ] ) ) {
					$defaults[ $key ] = $r[ $key ];
				}
			}

			foreach ( $r as $key => $value ) {
				if ( ! isset( $defaults[ $key ] ) ) {
					$defaults[ $key ] = $value;
				}
			}

			return $defaults;
		}

		return $r;
	}
}

if(!function_exists('current_url')) {
	function current_url() {
		return \Illuminate\Support\Facades\Request::fullUrl();
	}
}

if(!function_exists('remove_query_arg')) {
	function remove_query_arg( $key, $query = false ) {
		if ( is_array( $key ) ) { // removing multiple keys
			foreach ( $key as $k ) {
				$query = add_query_arg( $k, false, $query );
			}

			return $query;
		}

		return add_query_arg( $key, false, $query );
	}
}

if(!function_exists('add_query_arg')) {
	function add_query_arg( ...$args ) {
		$args = func_get_args();
		if ( is_array( $args[0] ) ) {
			if ( count( $args ) < 2 || false === $args[1] ) {
				$uri = $_SERVER['REQUEST_URI'];
			} else {
				$uri = $args[1];
			}
		} else {
			if ( count( $args ) < 3 || false === $args[2] ) {
				$uri = $_SERVER['REQUEST_URI'];
			} else {
				$uri = $args[2];
			}
		}

		if ( $frag = strstr( $uri, '#' ) ) {
			$uri = substr( $uri, 0, - strlen( $frag ) );
		} else {
			$frag = '';
		}

		if ( 0 === stripos( $uri, 'http://' ) ) {
			$protocol = 'http://';
			$uri      = substr( $uri, 7 );
		} elseif ( 0 === stripos( $uri, 'https://' ) ) {
			$protocol = 'https://';
			$uri      = substr( $uri, 8 );
		} else {
			$protocol = '';
		}

		if ( strpos( $uri, '?' ) !== false ) {
			list( $base, $query ) = explode( '?', $uri, 2 );
			$base .= '?';
		} elseif ( $protocol || strpos( $uri, '=' ) === false ) {
			$base  = $uri . '?';
			$query = '';
		} else {
			$base  = '';
			$query = $uri;
		}

		gmz_parse_str( $query, $qs );
		$qs = urlencode_deep( $qs ); // this re-URL-encodes things that were already in the query string
		if ( is_array( $args[0] ) ) {
			foreach ( $args[0] as $k => $v ) {
				$qs[ $k ] = $v;
			}
		} else {
			$qs[ $args[0] ] = $args[1];
		}

		foreach ( $qs as $k => $v ) {
			if ( $v === false ) {
				unset( $qs[ $k ] );
			}
		}

		$ret = build_query( $qs );
		$ret = trim( $ret, '?' );
		$ret = preg_replace( '#=(&|$)#', '$1', $ret );
		$ret = $protocol . $base . $ret . $frag;
		$ret = rtrim( $ret, '?' );

		return $ret;
	}
}

if(!function_exists('urlencode_deep')) {
	function urlencode_deep( $value ) {
		return map_deep( $value, 'urlencode' );
	}
}

if(!function_exists('build_query')) {
	function build_query( $data ) {
		return _http_build_query( $data, null, '&', '', false );
	}
}

if(!function_exists('gmz_parse_str')) {
	function gmz_parse_str( $string, &$array ) {
		parse_str( $string, $array );
	}
}

if(!function_exists('stripslashes_deep')) {
	function stripslashes_deep( $value ) {
		return map_deep( $value, 'stripslashes_from_strings_only' );
	}
}

if(!function_exists('stripslashes_from_strings_only')) {
	function stripslashes_from_strings_only( $value ) {
		return is_string( $value ) ? stripslashes( $value ) : $value;
	}
}

if(!function_exists('map_deep')) {
	function map_deep( $value, $callback ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $index => $item ) {
				$value[ $index ] = map_deep( $item, $callback );
			}
		} elseif ( is_object( $value ) ) {
			$object_vars = get_object_vars( $value );
			foreach ( $object_vars as $property_name => $property_value ) {
				$value->$property_name = map_deep( $property_value, $callback );
			}
		} else {
			$value = call_user_func( $callback, $value );
		}

		return $value;
	}
}

if(!function_exists('_http_build_query')) {
	function _http_build_query( $data, $prefix = null, $sep = null, $key = '', $urlencode = true ) {
		$ret = array();

		foreach ( (array) $data as $k => $v ) {
			if ( $urlencode ) {
				$k = urlencode( $k );
			}
			if ( is_int( $k ) && $prefix != null ) {
				$k = $prefix . $k;
			}
			if ( ! empty( $key ) ) {
				$k = $key . '%5B' . $k . '%5D';
			}
			if ( $v === null ) {
				continue;
			} elseif ( $v === false ) {
				$v = '0';
			}

			if ( is_array( $v ) || is_object( $v ) ) {
				array_push( $ret, _http_build_query( $v, '', $sep, $k, $urlencode ) );
			} elseif ( $urlencode ) {
				array_push( $ret, $k . '=' . urlencode( $v ) );
			} else {
				array_push( $ret, $k . '=' . $v );
			}
		}

		if ( null === $sep ) {
			$sep = ini_get( 'arg_separator.output' );
		}

		return implode( $sep, $ret );
	}
}

if(!function_exists('get_logo')) {
	function get_logo() {
		$option = get_option( 'logo', '' );
		$res    = false;
		if ( ! empty( $option ) ) {
			$url = get_attachment_url( $option );
			if ( ! empty( $url ) ) {
				$res = $url;
			}
		}

		return $res;
	}
}

if(!function_exists('get_favicon')) {
	function get_favicon() {
		$option = get_option( 'favicon', '' );
		if ( ! empty( $option ) ) {
			$url = get_attachment_url( $option );
			if ( ! empty( $url ) ) {
				return $url;
			}
		}

		return false;
	}
}

if(!function_exists('trim_text')) {
	function trim_text( $text, $number = 10 ) {
		$text = strip_tags( $text );
		if ( stripos( $text, " " ) ) {
			$str_s  = '';
			$ex_str = explode( " ", $text );
			if ( count( $ex_str ) > $number ) {
				for ( $i = 0; $i < $number; $i ++ ) {
					$str_s .= $ex_str[ $i ] . " ";
				}

				return $str_s;
			} else {
				return $text;
			}
		} else {
			return $text;
		}
	}
}

if(!function_exists('hexToHsl')) {
	function hexToHsl( $hex ) {
		$hex = str_split( ltrim( $hex, '#' ), 2 );
		$h   = $s = $l = 0;
		// convert the rgb values to the range 0-1
		$rgb = array_map( function ( $part ) {
			return hexdec( $part ) / 255;
		}, $hex );

		// find the minimum and maximum values of r, g and b
		$min = min( $rgb );
		$max = max( $rgb );

		// calculate the luminace value by adding the max and min values and divide by 2
		$l = ( $min + $max ) / 2;

		if ( $max === $min ) {
			$h = $s = 0;
		} else {
			if ( $l < 0.5 ) {
				$s = ( $max - $min ) / ( $max + $min );
			} elseif ( $l > 0.5 ) {
				$s = ( $max - $min ) / ( 2 - $max - $min );
			}

			if ( $max === $rgb[0] ) {
				$h = ( $rgb[1] - $rgb[2] ) / ( $max - $min );
			} elseif ( $max === $rgb[1] ) {
				$h = 2 + ( $rgb[2] - $rgb[0] ) / ( $max - $min );
			} elseif ( $max === $rgb[2] ) {
				$h = 4 + ( $rgb[0] - $rgb[1] ) / ( $max - $min );
			}

			$h = $h * 60;

			if ( $h < 0 ) {
				$h = $h + 360;
			}
		}

		return [ $h, $s, $l ];
	}
}

if(!function_exists('get_date_format')) {
	function get_date_format( $time = false ) {
		if ( $time ) {
			$time_type = get_option( 'time_type', '12' );
			if ( $time_type == 12 ) {
				return get_option( 'date_format', 'd/m/Y' ) . ' ' . get_option( 'time_format', 'h:i A' );
			} else {
				return get_option( 'date_format', 'd/m/Y' ) . ' ' . get_option( 'time_format', 'H:i' );
			}
		} else {
			return get_option( 'date_format', 'd/m/Y' );
		}
	}
}

if(!function_exists('get_time_format')) {
	function get_time_format() {
		$time_type = get_option( 'time_type', '12' );
		if ( $time_type == 12 ) {
			return get_option( 'time_format', 'h:i A' );
		} else {
			return get_option( 'time_format', 'H:i' );
		}
	}
}

if(!function_exists('convert_realtime')) {
	function convert_realtime( $datetime ) {
		$time         = strtotime( $datetime );
		$current_time = TIME();
		if ( $time > strtotime( '-1 hours', $current_time ) ) {
			$t = round( ( ( $current_time - $time ) / 60 ) );
			$t = ( $current_time - $time );
			if ( $t == 1 ) {
				$type = __( 'minute ago' );
			} else {
				$type = __( 'minutes ago' );
			}
			$t = $t . " " . $type;
		} elseif ( $time > strtotime( '-1 days', $current_time ) ) {
			$t = round( ( ( $current_time - $time ) / 3600 ) );
			if ( $t == 1 ) {
				$type = __( 'hour ago' );
			} else {
				$type = __( 'hours ago' );
			}
			$t = $t . " " . $type;
		} else {
			$t = date( get_date_format(), $time );
		}

		return $t;
	}
}

if(!function_exists('get_date_format_moment')) {
	function get_date_format_moment() {
		$format = get_date_format();
		$format = str_replace( 'j', 'd', $format );
		$format = str_replace( 'S', 'd', $format );
		$format = str_replace( 'n', 'm', $format );

		$ori_format = [
			'd' => 'DD',
			'm' => 'MM',
			'M' => 'MM',
			'Y' => 'YYYY',
			'y' => 'YY',
			'F' => 'MM',
		];

		preg_match_all( "/[a-zA-Z]/", $format, $out );

		$out = $out[0];
		foreach ( $out as $key => $val ) {
			foreach ( $ori_format as $ori_key => $ori_val ) {
				if ( $val == $ori_key ) {
					$format = str_replace( $val, $ori_val, $format );
				}
			}
		}

		return $format;
	}
}

if(!function_exists('gmz_date_diff')) {
	function gmz_date_diff( $start, $end, $type = 'date', $rounding = true ) {
		switch ( $type ) {
			case 'date':
				$start = date_create( date( 'Y-m-d', $start ) );
				$end   = date_create( date( 'Y-m-d', $end ) );
				$diff  = date_diff( $start, $end );

				return $diff->format( "%a" );
				break;
			case 'hour':
				$diff   = $end - $start;
				$minute = (int) ( $diff / 60 );
				if ( $minute <= 0 ) {
					$minute = 1;
				}
				if ( $diff % 60 ) {
					$minute += 1;
				}

				return ceil( $minute / 60 );
				break;
			case 'minute':
				$diff   = $end - $start;
				$minute = (int) ( $diff / 60 );
				if ( $minute <= 0 ) {
					$minute = 1;
				}
				if ( $diff % 60 ) {
					$minute += 1;
				}

				return $minute;
				break;
			case 'second':
				return $end - $start;
				break;
		}

	}
}

if(!function_exists('list_countries')) {
	function list_countries( $key = null ) {
		$countries      = [];
		$countries_data = file_get_contents( public_path( 'vendors/countries/countries.json' ) );
		$countries_data = json_decode( $countries_data, true );
		if ( ! empty( $countries_data ) && is_array( $countries_data ) ) {
			foreach ( $countries_data as $country ) {
				$countries[ $country['id'] ] = $country['name'];
			}
		}

		if ( $key ) {
			return ( isset( $countries[ $key ] ) ) ? $countries[ $key ] : '';
		}

		return $countries;
	}
}

if(!function_exists('get_list_time')) {
	function get_list_time( $step = GMZ_TIME_STEP ) {
		$start_time  = new \DateTime('2010-01-01 00:00');
		$end_time    = new \DateTime('2010-01-01 23:59');
		$results  = array();

		$time_type = get_option( 'time_type', '12' );

		if($time_type == '12'){
			$time_format = 'h:i A';
		}else{
			$time_format = 'H:i';
		}

		while($start_time <= $end_time)
		{
			$time = $start_time->format($time_format);
			$time_key = $time;
			$str_start = substr($time, 0, 2);
			$str_end = substr($time, strlen($time) - 2, 2);
			if($time_type == '12' && $str_start == '12' && in_array($str_end, ['AM', 'am'])){
				$time = '00' . substr($time, 2);
			}
			$results[$time_key] = $time;
			$start_time->add(new \DateInterval('PT'.$step.'M'));
		}

		return $results;
	}
}

if(!function_exists('build_query_sort')) {
	function build_query_sort( $orderBy ) {
		$get_params = request()->all();
		$order      = request()->get( 'order', 'DESC' );
		$new_params = [
			'orderby' => $orderBy,
			'order'   => ( $order == 'DESC' ) ? 'ASC' : 'DESC'
		];
		$get_params = gmz_parse_args( $new_params, $get_params );

		return http_build_query( $get_params );
	}
}

if(!function_exists('get_icon_sort')) {
	function get_icon_sort( $orderBy ) {
		$currentOrderBy = request()->get( 'orderby' );
		$currentOrder   = request()->get( 'order' );
		if ( $orderBy == $currentOrderBy ) {
			if ( $currentOrder == "ASC" ) {
				$icon = "<i class=\"fas fa-sort-up\"></i>";
			} else {
				$icon = "<i class=\"fas fa-sort-down\"></i>";
			}
		} else {
			$icon = "<i class=\"fas fa-sort\"></i>";
		}

		return $icon;
	}
}

if(!function_exists('selected')) {
	function selected( $current, $default ) {
		if ( trim( $current ) == trim( $default ) ) {
			return 'selected';
		}

		return '';
	}
}

if(!function_exists('set_env')) {
	function set_env( $key = 'APP_KEY', $key_value = '' ) {
		$path = base_path( '.env' );
		if ( file_exists( $path ) ) {
			$file_content = file_get_contents( $path );
			if(strpos($file_content, $key) !== false) {
				file_put_contents( $path, str_replace(
					$key . '=' . env( $key ), $key . '=' . $key_value, $file_content
				) );
			}else{
				$file_content .= "\n" . $key . "=" . $key_value;
				file_put_contents( $path, $file_content);
			}
		}
	}
}

if(!function_exists('get_time_release')) {
	function get_time_release( $older_date, $newer_date = false ) {
		$unknown_text   = 'some minutes';
		$right_now_text = 'right now';
		$ago_text       = '%s ago';
		$chunks         = [
			[ 60 * 60 * 24 * 365, 'year', 'years' ],
			[ 60 * 60 * 24 * 30, 'month', 'months' ],
			[ 60 * 60 * 24 * 7, 'week', 'weeks' ],
			[ 60 * 60 * 24, 'day', 'days' ],
			[ 60 * 60, 'hour', 'hours' ],
			[ 60, 'minute', 'minutes' ],
			[ 1, 'second', 'seconds' ]
		];
		if ( ! empty( $older_date ) && ! is_numeric( $older_date ) ) {
			$time_chunks = explode( ':', str_replace( ' ', ':', $older_date ) );
			$date_chunks = explode( '-', str_replace( ' ', '-', $older_date ) );
			$older_date  = gmmktime( (int) $time_chunks[1], (int) $time_chunks[2], (int) $time_chunks[3], (int) $date_chunks[1], (int) $date_chunks[2], (int) $date_chunks[0] );
		}
		$newer_date = ( ! $newer_date ) ? time() : $newer_date;
		$since      = $newer_date - $older_date;

		if ( 0 > $since ) {
			$output = $unknown_text;
		} else {
			for ( $i = 0, $j = count( $chunks ); $i < $j; ++ $i ) {
				$seconds = $chunks[ $i ][0];
				$count   = floor( $since / $seconds );
				if ( 0 != $count ) {
					break;
				}
			}

			if ( ! isset( $chunks[ $i ] ) ) {
				$output = $right_now_text;
			} else {
				$output = ( 1 == $count ) ? '1 ' . $chunks[ $i ][1] : $count . ' ' . $chunks[ $i ][2];
				if ( $i + 2 < $j ) {
					$seconds2 = $chunks[ $i + 1 ][0];
					$name2    = $chunks[ $i + 1 ][1];
					$count2   = floor( ( $since - ( $seconds * $count ) ) / $seconds2 );

					if ( 0 != $count2 ) {
						$output .= ( 1 == $count2 ) ? ',' . ' 1 ' . $name2 : ',' . ' ' . $count2 . ' ' . $chunks[ $i + 1 ][2];
					}
				}
				if ( ! (int) trim( $output ) ) {
					$output = $right_now_text;
				}
			}
		}

		if ( $output != $right_now_text ) {
			$output = sprintf( $ago_text, $output );
		}

		return $output;
	}
}

if(!function_exists('send_curl')) {
	function send_curl( $url, $data = [] ) {
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_POST, count( $data ) );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		$result = curl_exec( $ch );
		curl_close( $ch );

		return $result;
	}
}

if(!function_exists('hours_to_seconds')) {
	function hours_to_seconds( $str_time) {
		sscanf( $str_time, "%d:%d", $hours, $minutes );

		return ( $hours * 3600 + $minutes * 60 );
	}
}

if(!function_exists('seconds_to_hours')) {
	function seconds_to_hours( $seconds ) {
		return gmdate( "H:i", $seconds );
	}
}

if(!function_exists('register_style')) {
    function register_style( $handle, $url, $queue = false, $v = '') {
        \App\Modules\Frontend\Controllers\ScriptController::inst()->_addStyle($handle, $url, $queue, $v);
    }
}

if(!function_exists('register_script')) {
    function register_script( $handle, $url, $queue = false, $v = '') {
        \App\Modules\Frontend\Controllers\ScriptController::inst()->_addScript($handle, $url, $queue, $v);
    }
}

if(!function_exists('admin_register_style')) {
    function admin_register_style( $handle, $url, $queue = false, $v = '') {
        \App\Modules\Backend\Controllers\ScriptController::inst()->_addStyle($handle, $url, $queue, $v);
    }
}

if(!function_exists('admin_register_script')) {
    function admin_register_script( $handle, $url, $queue = false, $v = '') {
        \App\Modules\Backend\Controllers\ScriptController::inst()->_addScript($handle, $url, $queue, $v);
    }
}

if(!function_exists('add_action')){
    function add_action($handler, $class, $piority, $numParams){
        \TorMorten\Eventy\Facades\Eventy::addAction($handler, $class, $piority, $numParams);
    }
}

if(!function_exists('add_filter')){
    function add_filter($handler, $class, $piority, $numParams){
        \TorMorten\Eventy\Facades\Eventy::addFilter($handler, $class, $piority, $numParams);
    }
}

if(!function_exists('apply_filter')){
    function apply_filter($handler, ...$params){
        return \TorMorten\Eventy\Facades\Eventy::filter($handler, ...$params);
    }
}

if(!function_exists('do_action')){
    function do_action($handler, ...$params){
        \TorMorten\Eventy\Facades\Eventy::action($handler, ...$params);
    }
}