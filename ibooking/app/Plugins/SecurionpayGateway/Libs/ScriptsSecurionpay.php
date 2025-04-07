<?php
if ( ! defined( 'GMZPATH' ) ) { exit; }
use TorMorten\Eventy\Facades\Events as Eventy;

if(!class_exists('ScriptsSecurionpay')) {
    class ScriptsSecurionpay
    {
        static $_inst = null;

        public function __construct()
        {
            Eventy::addAction('gmz_init_header', [$this, '_addStyle'], 20, 1);
            Eventy::addAction('gmz_init_footer', [$this, '_addScript'], 20, 1);
        }

        public function _addStyle()
        {
            $current_route = \Request::route()->getName();
            if ($current_route == 'checkout') {
                $enabled = get_option('payment_securionpay_enable', 'off');
                if ($enabled == 'on') {
                    $publicKey = get_option('payment_securionpay_public_key', '');
                    ?>
                    <script>
                        var gmz_securionpay_params = {
                            publicKey: '<?php echo $publicKey; ?>',
                        }
                    </script>
                    <?php
                }
            }
        }

        public function _addScript()
        {
            $current_route = \Request::route()->getName();
            if ($current_route == 'checkout') {
                $enabled = get_option('payment_securionpay_enable', 'off');
                if ($enabled == 'on') {
                    echo '<script src="https://securionpay.com/checkout.js"></script>';
                    echo '<script src="' . url('plugins/securionpaygateway/js/securionpay.js') . '"></script>';
                }
            }
        }

        public static function inst()
        {
            if (is_null(self::$_inst)) {
                self::$_inst = new self();
            }
            return self::$_inst;
        }
    }

    ScriptsSecurionpay::inst();
}