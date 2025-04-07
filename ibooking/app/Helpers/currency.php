<?php
if(!function_exists('default_currencies')) {
	function default_currencies() {
		$currencies = array(
			'ALL' => 'Albania Lek',
			'AFN' => 'Afghanistan Afghani',
			'AMD' => 'Armenian Dram',
			'ARS' => 'Argentina Peso',
			'AWG' => 'Aruba Guilder',
			'AUD' => 'Australia Dollar',
			'AZN' => 'Azerbaijan New Manat',
			'BSD' => 'Bahamas Dollar',
			'BBD' => 'Barbados Dollar',
			'BDT' => 'Bangladeshi taka',
			'BYR' => 'Belarus Ruble',
			'BZD' => 'Belize Dollar',
			'BMD' => 'Bermuda Dollar',
			'BOB' => 'Bolivia Boliviano',
			'BAM' => 'Bosnia and Herzegovina Convertible Marka',
			'BWP' => 'Botswana Pula',
			'BGN' => 'Bulgaria Lev',
			'BRL' => 'Brazil Real',
			'BND' => 'Brunei Darussalam Dollar',
			'KHR' => 'Cambodia Riel',
			'CAD' => 'Canada Dollar',
			'KYD' => 'Cayman Islands Dollar',
			'CLP' => 'Chile Peso',
			'CNY' => 'China Yuan Renminbi',
			'COP' => 'Colombia Peso',
			'CRC' => 'Costa Rica Colon',
			'HRK' => 'Croatia Kuna',
			'CUP' => 'Cuba Peso',
			'CZK' => 'Czech Republic Koruna',
			'DKK' => 'Denmark Krone',
			'DOP' => 'Dominican Republic Peso',
			'XCD' => 'East Caribbean Dollar',
			'EGP' => 'Egypt Pound',
			'SVC' => 'El Salvador Colon',
			'EEK' => 'Estonia Kroon',
			'EUR' => 'Euro Member Countries',
			'FKP' => 'Falkland Islands (Malvinas) Pound',
			'FJD' => 'Fiji Dollar',
			'GHC' => 'Ghana Cedis',
			'GIP' => 'Gibraltar Pound',
			'GTQ' => 'Guatemala Quetzal',
			'GGP' => 'Guernsey Pound',
			'GYD' => 'Guyana Dollar',
			'HNL' => 'Honduras Lempira',
			'HKD' => 'Hong Kong Dollar',
			'HUF' => 'Hungary Forint',
			'ISK' => 'Iceland Krona',
			'INR' => 'India Rupee',
			'IDR' => 'Indonesia Rupiah',
			'IRR' => 'Iran Rial',
			'IMP' => 'Isle of Man Pound',
			'ILS' => 'Israel Shekel',
			'JMD' => 'Jamaica Dollar',
			'JPY' => 'Japan Yen',
			'JEP' => 'Jersey Pound',
			'KZT' => 'Kazakhstan Tenge',
			'KPW' => 'Korea (North) Won',
			'KRW' => 'Korea (South) Won',
			'KGS' => 'Kyrgyzstan Som',
			'LAK' => 'Laos Kip',
			'LVL' => 'Latvia Lat',
			'LBP' => 'Lebanon Pound',
			'LRD' => 'Liberia Dollar',
			'LTL' => 'Lithuania Litas',
			'MKD' => 'Macedonia Denar',
			'MYR' => 'Malaysia Ringgit',
			'MUR' => 'Mauritius Rupee',
			'MAD' => 'Moroccan Dirham',
			'MXN' => 'Mexico Peso',
			'MNT' => 'Mongolia Tughrik',
			'MZN' => 'Mozambique Metical',
			'NAD' => 'Namibia Dollar',
			'NPR' => 'Nepal Rupee',
			'ANG' => 'Netherlands Antilles Guilder',
			'NZD' => 'New Zealand Dollar',
			'NIO' => 'Nicaragua Cordoba',
			'NGN' => 'Nigeria Naira',
			'NOK' => 'Norway Krone',
			'OMR' => 'Oman Rial',
			'PKR' => 'Pakistan Rupee',
			'PAB' => 'Panama Balboa',
			'PYG' => 'Paraguay Guarani',
			'PEN' => 'Peru Nuevo Sol',
			'PHP' => 'Philippines Peso',
			'PLN' => 'Poland Zloty',
			'QAR' => 'Qatar Riyal',
			'RON' => 'Romania New Leu',
			'RUB' => 'Russia Ruble',
			'SHP' => 'Saint Helena Pound',
			'SAR' => 'Saudi Arabia Riyal',
			'RSD' => 'Serbia Dinar',
			'SCR' => 'Seychelles Rupee',
			'SGD' => 'Singapore Dollar',
			'SBD' => 'Solomon Islands Dollar',
			'SOS' => 'Somalia Shilling',
			'ZAR' => 'South Africa Rand',
			'LKR' => 'Sri Lanka Rupee',
			'SEK' => 'Sweden Krona',
			'CHF' => 'Switzerland Franc',
			'SRD' => 'Suriname Dollar',
			'SYP' => 'Syria Pound',
			'TWD' => 'Taiwan New Dollar',
			'THB' => 'Thailand Baht',
			'TTD' => 'Trinidad and Tobago Dollar',
			'TRY' => 'Turkey Lira',
			'TRL' => 'Turkey Lira',
			'TVD' => 'Tuvalu Dollar',
			'UAH' => 'Ukraine Hryvna',
			'GBP' => 'United Kingdom Pound',
			'USD' => 'United States Dollar',
			'UYU' => 'Uruguay Peso',
			'UZS' => 'Uzbekistan Som',
			'VEF' => 'Venezuela Bolivar',
			'VND' => 'Viet Nam Dong',
			'YER' => 'Yemen Rial',
			'ZWD' => 'Zimbabwe Dollar'
		);

		return $currencies;
	}
}

if(!function_exists('all_languages')) {
	function all_languages( $key = '' ) {
		$language_codes = array(
			'en' => 'English',
			'aa' => 'Afar',
			'ab' => 'Abkhazian',
			'af' => 'Afrikaans',
			'am' => 'Amharic',
			'ar' => 'Arabic',
			'as' => 'Assamese',
			'ay' => 'Aymara',
			'az' => 'Azerbaijani',
			'ba' => 'Bashkir',
			'be' => 'Byelorussian',
			'bg' => 'Bulgarian',
			'bh' => 'Bihari',
			'bi' => 'Bislama',
			'bn' => 'Bengali/Bangla',
			'bo' => 'Tibetan',
			'br' => 'Breton',
			'ca' => 'Catalan',
			'co' => 'Corsican',
			'cs' => 'Czech',
			'cy' => 'Welsh',
			'da' => 'Danish',
			'de' => 'German',
			'dz' => 'Bhutani',
			'el' => 'Greek',
			'eo' => 'Esperanto',
			'es' => 'Spanish',
			'et' => 'Estonian',
			'eu' => 'Basque',
			'fa' => 'Persian',
			'fi' => 'Finnish',
			'fj' => 'Fiji',
			'fo' => 'Faeroese',
			'fr' => 'French',
			'fy' => 'Frisian',
			'ga' => 'Irish',
			'gd' => 'Scots/Gaelic',
			'gl' => 'Galician',
			'gn' => 'Guarani',
			'gu' => 'Gujarati',
			'ha' => 'Hausa',
			'hi' => 'Hindi',
			'hr' => 'Croatian',
			'hu' => 'Hungarian',
			'hy' => 'Armenian',
			'ia' => 'Interlingua',
			'ie' => 'Interlingue',
			'ik' => 'Inupiak',
			'in' => 'Indonesian',
			'is' => 'Icelandic',
			'it' => 'Italian',
			'iw' => 'Hebrew',
			'ja' => 'Japanese',
			'ji' => 'Yiddish',
			'jw' => 'Javanese',
			'ka' => 'Georgian',
			'kk' => 'Kazakh',
			'kl' => 'Greenlandic',
			'km' => 'Cambodian',
			'kn' => 'Kannada',
			'ko' => 'Korean',
			'ks' => 'Kashmiri',
			'ku' => 'Kurdish',
			'ky' => 'Kirghiz',
			'la' => 'Latin',
			'ln' => 'Lingala',
			'lo' => 'Laothian',
			'lt' => 'Lithuanian',
			'lv' => 'Latvian/Lettish',
			'mg' => 'Malagasy',
			'mi' => 'Maori',
			'mk' => 'Macedonian',
			'ml' => 'Malayalam',
			'mn' => 'Mongolian',
			'mo' => 'Moldavian',
			'mr' => 'Marathi',
			'ms' => 'Malay',
			'mt' => 'Maltese',
			'my' => 'Burmese',
			'na' => 'Nauru',
			'ne' => 'Nepali',
			'nl' => 'Dutch',
			'no' => 'Norwegian',
			'oc' => 'Occitan',
			'om' => '(Afan)/Oromoor/Oriya',
			'pa' => 'Punjabi',
			'pl' => 'Polish',
			'ps' => 'Pashto/Pushto',
			'pt' => 'Portuguese',
			'qu' => 'Quechua',
			'rm' => 'Rhaeto-Romance',
			'rn' => 'Kirundi',
			'ro' => 'Romanian',
			'ru' => 'Russian',
			'rw' => 'Kinyarwanda',
			'sa' => 'Sanskrit',
			'sd' => 'Sindhi',
			'sg' => 'Sangro',
			'sh' => 'Serbo-Croatian',
			'si' => 'Singhalese',
			'sk' => 'Slovak',
			'sl' => 'Slovenian',
			'sm' => 'Samoan',
			'sn' => 'Shona',
			'so' => 'Somali',
			'sq' => 'Albanian',
			'sr' => 'Serbian',
			'ss' => 'Siswati',
			'st' => 'Sesotho',
			'su' => 'Sundanese',
			'sv' => 'Swedish',
			'sw' => 'Swahili',
			'ta' => 'Tamil',
			'te' => 'Tegulu',
			'tg' => 'Tajik',
			'th' => 'Thai',
			'ti' => 'Tigrinya',
			'tk' => 'Turkmen',
			'tl' => 'Tagalog',
			'tn' => 'Setswana',
			'to' => 'Tonga',
			'tr' => 'Turkish',
			'ts' => 'Tsonga',
			'tt' => 'Tatar',
			'tw' => 'Twi',
			'uk' => 'Ukrainian',
			'ur' => 'Urdu',
			'uz' => 'Uzbek',
			'vi' => 'Vietnamese',
			'vo' => 'Volapuk',
			'wo' => 'Wolof',
			'xh' => 'Xhosa',
			'yo' => 'Yoruba',
			'zh' => 'Chinese',
			'zu' => 'Zulu',
		);

		if ( $key ) {
			return isset( $language_codes[ $key ] ) ? $language_codes[ $key ] : '';
		}

		return $language_codes;
	}
}

if(!function_exists('list_currencies')) {
	function list_currencies( $option = false ) {
		$return     = [];
		$currencies = get_option( 'currencies', [] );
		if ( $option ) {
			foreach ( $currencies as $currency ) {
				$return[ $currency['unit'] ] = $currency['name'];
			}
		} else {
			$return = $currencies;
		}

		return $return;
	}
}

if(!function_exists('get_currency_by_unit')) {
	function get_currency_by_unit( $unit = '', $return = '' ) {
		$list_currencies = list_currencies();
		foreach ( $list_currencies as $currency ) {
			if ( isset( $currency['unit'] ) && $currency['unit'] == $unit ) {
				return ( ! empty( $return ) && isset( $currency[ $return ] ) ) ? $currency[ $return ] : $currency;
				break;
			}
		}

		return false;
	}
}

if(!function_exists('convert_price')) {
	function convert_price( $price, $show_symbol = true, $format = true, $_currency = null ) {
		return \Currency::get_inst()->convertPrice( $price, $show_symbol, $format, $_currency );
	}
}

if(!function_exists('get_symbol_currency')) {
	function get_symbol_currency( $position = true ) {
		return \Currency::get_inst()->getSymbolCurrency( $position );
	}
}

if(!function_exists('current_currency')) {
	function current_currency( $key = '' ) {
		return \Currency::get_inst()->currentCurrency( $key );
	}
}

if(!function_exists('primary_currency')) {
	function primary_currency( $key = '' ) {
		return \Currency::get_inst()->primaryCurrency();
	}
}

if(!function_exists('get_range_extension')) {
	function get_range_extension() {
		$position = current_currency();
		$data     = [
			'prefix'  => '',
			'postfix' => ''
		];
		$symbol   = esc_html( $position['symbol'] );
		switch ( $position['position'] ) {
			case 'left':
			default:
				$data['prefix'] = $symbol;
				break;
			case 'left_space':
				$data['prefix'] = $symbol . ' ';
				break;
			case 'right':
				$data['postfix'] = $symbol;
				break;
			case 'right_space':
				$data['postfix'] = ' ' . $symbol;
				break;
		}

		return $data;
	}
}

if(!function_exists('is_multi_currency')) {
	function is_multi_currency() {
		$currencies = list_currencies();
		if ( count( $currencies ) == 1 || empty( $currencies ) ) {
			return false;
		}

		return true;
	}
}

if(!function_exists('get_dropdown_currency')) {
	function get_dropdown_currency() {
		$currencies       = list_currencies();
		$current_currency = current_currency();
		$current_params   = '';
		$params           = $_GET; // Get Current Params
		if ( array_key_exists( 'currency', $params ) ) {
			unset( $params['currency'] );
		}
		if ( ! empty( $params ) ) {
			$current_params = '&' . http_build_query( $params );
		}

		ob_start();

		?>
        <div class="select-language dropdown">

			<?php foreach ( $currencies as $currency ): ?>
				<?php if ( $current_currency['unit'] == $currency['unit'] ): ?>
                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
						<?php echo esc_html( get_translate( $currency['name'] ) ); ?>
                    </button>
					<?php break; ?>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php if ( count( $currencies ) > 1 ): ?>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<?php foreach ( $currencies as $currency ): ?>
						<?php if ( $current_currency['unit'] == $currency['unit'] ) {
							continue;
						} ?>
                        <a class="dropdown-item" href="?currency=<?php echo $currency['unit'] . $current_params ?>">
							<?php echo esc_html( get_translate( $currency['name'] ) ); ?>
                        </a>
					<?php endforeach; ?>
                </div>
			<?php endif; ?>
        </div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}