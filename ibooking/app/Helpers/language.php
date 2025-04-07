<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/7/20
 * Time: 14:25
 */

if(!function_exists('get_current_language')) {
	function get_current_language() {
		return app()->getLocale();
	}
}

if(!function_exists('render_flag_option')) {
	function render_flag_option( $class = '' ) {
		$langs = get_languages( true );
		if ( count( $langs ) > 0 ) {
			?>
            <div id="gmz-language-action" style="display: none"
                 class="gmz-language-action <?php echo esc_attr( $class ) ?>">
                <ul>
					<?php foreach ( $langs as $k => $lang ) { ?>
                        <li>
                            <a href="javascript:void(0);" class="item <?php echo esc_attr( $k == 0 ? 'active' : '' ) ?>"
                               data-code="<?php echo esc_attr( $lang['code'] ); ?>">
                                <img
                                        src="<?php echo esc_attr( asset( 'vendors/countries/flag/32x32/' . $lang['flag_code'] . '.png' ) ) ?>"/>
                            </a>
                        </li>
					<?php } ?>
                </ul>
            </div>
			<?php
		}
	}
}

if(!function_exists('is_multi_language')) {
	function is_multi_language() {
		$option = get_option( 'multi_language', 'off' );
		if ( $option == 'on' ) {
			return true;
		} else {
			return false;
		}
	}
}

if(!function_exists('get_lang_class')) {
	function get_lang_class( $key, $item ) {
		$class = [];
		if ( $key > 0 ) {
			array_push( $class, 'hidden' );
		}
		if ( ! empty( $item ) ) {
			array_push( $class, 'has-translation' );
		}
		if ( ! empty( $class ) ) {
			return ' ' . implode( ' ', $class );
		}

		return '';
	}
}

if(!function_exists('get_lang_attribute')) {
	function get_lang_attribute( $item ) {
		if ( ! empty( $item ) ) {
			return 'data-lang="' . $item . '"';
		}

		return '';
	}
}

if(!function_exists('get_lang_suffix')) {
	function get_lang_suffix( $code ) {
		if ( ! empty( $code ) ) {
			return '_' . $code;
		}

		return '';
	}
}

if(!function_exists('get_languages_field')) {
	function get_languages_field() {
		$langs = get_languages();
		if ( count( $langs ) == 0 ) {
			$langs[] = '';
		}

		return $langs;
	}
}

if(!function_exists('get_languages')) {
	function get_languages( $full = false ) {
		if ( is_multi_language() ) {
			$langs = \App\Repositories\LanguageRepository::inst()->getAllLanguages();
			if ( ! $full ) {
				$codes = [];
				if ( ! $langs->isEmpty() ) {
					foreach ( $langs as $item ) {
						array_push( $codes, $item->code );
					}
				}

				return $codes;
			}

			return $langs;
		}

		return [];
	}
}

if(!function_exists('get_current_language_data')) {
	function get_current_language_data() {
		$langs             = get_languages( true );
		$current_lang_code = get_current_language();
		if ( ! empty( $langs ) ) {
			foreach ( $langs as $key => $lang ) {
				if ( $current_lang_code == $lang['code'] ) {
					return $lang;
				}
			}
		}

		return [];
	}
}

if(!function_exists('get_translate_old')) {
    function get_translate_old($text, $lang = '', $format = false) {
	    $ori_name = $text;
	    if ( gettype( $text ) == 'string' ) {
		    if ( empty( $lang ) ) {
			    $lang = get_current_language();
		    }
		    if ( $format ) {
			    $text = preg_replace( "/(\<p\>)(\[)(:|:[a-zA-Z_-]*)(\])(\<\/p\>)/", "$2$3$4", $text );
		    }
		    preg_match_all( "/(?<=\[:" . $lang . "\]).*?([^:\[\]]+)(?=\[:)/s", $text, $text );

		    $text = (array) $text;

		    if ( ! empty( $text ) && isset( $text[0][0] ) ) {
			    return lang_clean_text( $text[0][0] );
		    } elseif ( ! empty( $ori_name ) ) {
			    $has_lang = strpos( $ori_name, '[:' );
			    if ( $has_lang !== false ) {
				    $temp_origin_name = $ori_name;
				    $lang_first       = get_current_language();
				    preg_match_all( "/(?<=\[:" . $lang_first . "\]).*?([^:\[\]]+)(?=\[:)/s", $temp_origin_name, $temp_origin_name );
				    $temp_origin_name = (array) $temp_origin_name;
				    if ( ! empty( $temp_origin_name ) && isset( $temp_origin_name[0][0] ) ) {
					    return lang_clean_text( $temp_origin_name[0][0] );
				    } else {
					    $text_arr = explode( '[:', $ori_name );
					    if ( isset( $text_arr[1] ) && ! empty( $text_arr[1] ) ) {
						    $text_temp = substr( $text_arr[1], 3, strlen( $text_arr[1] ) );

						    return lang_clean_text( $text_temp );
					    } else {
						    return lang_clean_text( $ori_name );
					    }
				    }
			    } else {
				    return lang_clean_text( $ori_name );
			    }
		    }

		    return '';
	    } else {
		    return trim( $text );
	    }
    }
}

if(!function_exists('lang_clean_text')) {
	function lang_clean_text( $text ) {
		$text = preg_replace( '/\[:(.*?)\]/', '', $text );
		if ( strtolower( $text ) == 'array' ) {
			$text = '';
		}

		return $text;
	}
}

if(!function_exists('set_translate')) {
	function set_translate( $field_name = '' ) {

		$text = '';
		if ( is_multi_language() ) {
			$langs = get_languages();
			if ( ! empty( $langs ) ) {
				foreach ( $langs as $key => $code ) {
					$input_name = $field_name . '_' . $code;
					if ( isset( $_POST[ $input_name ] ) ) {
						$text .= '[:' . $code . ']' . request()->get( $input_name, '' );
					} else {
						$text .= '[:' . $code . ']' . request()->get( $field_name, '' );
					}
				}
				$text .= '[:]';
			} else {
				$text = request()->get( $field_name, '' );
			}
		} else {
			$text = request()->get( $field_name, '' );
		}

		return $text;
	}
}

if(!function_exists('get_dropdown_language')) {
	function get_dropdown_language() {
		if ( ! is_multi_language() ) {
			return false;
		}

		$langs = get_languages( true );
		if ( count( $langs ) > 1 ) {
			$current_session = get_current_language();
			$current_params  = '';
			$current_lang    = $langs[0];

			foreach ( $langs as $item ) {
				if ( ( $item['code'] == $current_session ) ) {
					$current_lang = $item;
				}
			}
			$params = $_GET; // Get Current Params

			if ( array_key_exists( 'lang', $params ) ) {
				unset( $params['lang'] );
			}
			if ( ! empty( $params ) ) {
				$current_params = '&' . http_build_query( $params );
			}
			ob_start();
			?>
            <div class="select-language dropdown">

                <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <span class="flag-icon flag-icon-<?php echo $current_lang['flag_code']; ?>"></span>
					<?php echo esc_html( $current_lang['name'] ); ?>
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<?php foreach ( $langs as $lang ): ?>
						<?php if ( $lang['code'] !== $current_lang['code'] ) { ?>
                            <a class="dropdown-item" href="?lang=<?php echo $lang['code'] . $current_params ?>">
                                <span class="flag-icon flag-icon-<?php echo $lang['flag_code'] ?>"></span>
								<?php echo esc_html( $lang['name'] ); ?>
                            </a>
						<?php } ?>
					<?php endforeach; ?>
                </div>
            </div>
			<?php
			$content = ob_get_contents();
			ob_end_clean();

			return $content;
		}

		return false;
	}
}

if(!function_exists('rtl_class')) {
	function rtl_class() {
		$class_rtl = '';
		$is_rtl    = get_option( 'is_rtl', 'off' );
		if ( $is_rtl == 'on' ) {
			$class_rtl = 'is-rtl';
		}
		if ( is_multi_language() ) {
			$current_lang = get_current_language_data();
			if ( ! empty( $current_lang ) ) {
				if ( $current_lang['rtl'] == 'on' ) {
					$class_rtl = 'is-rtl';
				} else {
					$class_rtl = '';
				}
			}
		}

		return $class_rtl;
	}
}

if(!function_exists('get_content_language')) {
    function get_content_language($text, $start, $end)
    {
        $pos = strpos($text, $start);
        if ($pos === FALSE) {
            return false;
        }
        $pos += strlen($start);
        $contentLength = strpos($text, $end, $pos) - $pos;
        return substr($text, $pos, $contentLength);
    }
}

if(!function_exists('get_translate')) {
    function get_translate($text, $lang = '', $format = false)
    {
        $textOrigin = $text;

        if (strpos($text, '[:]') !== FALSE) {
            if (empty($lang)) {
                $lang = get_current_language();
            }

            $startLang = '[:' . trim($lang) . ']';
            $endLang = '[:';

            $text = get_content_language($textOrigin, $startLang, $endLang);
            if (!$text) {
                $lang = get_current_language();
                $startLang = '[:' . trim($lang) . ']';
                $text = get_content_language($textOrigin, $startLang, $endLang);
            }
        }

        if(empty($text)){
            $text = lang_clean_text($textOrigin);
        }

        return $text;
    }
}

if(!function_exists('ilangs')){
    function ilangs($text){
        return $text;
    }
}