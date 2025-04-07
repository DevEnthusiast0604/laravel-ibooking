<?php
if ( ! defined( 'GMZPATH' ) ) { exit; }
use TorMorten\Eventy\Facades\Events as Eventy;
if(!class_exists('InvoiceCore')) {
    class InvoiceCore
    {
        private static $_inst;

        public function __construct()
        {
            //$this->enqueueScripts();
            Eventy::addAction('gmz_complete_order_links', [$this, '_addCompleteOrderLink'], 20, 1);
            Eventy::addAction('gmz_my_order_actions', [$this, '_addInvoiceButton'], 20, 1);
            Eventy::addAction('gmz_order_group_actions', [$this, '_addAdminInvoiceButton'], 20, 1);
        }

        public function _addAdminInvoiceButton($order){
            ?>
            <a class="dropdown-item" href="<?php echo esc_url(url('booking/invoice/' . $order['order_token'])) ?>" target="_blank">
                <?php echo __('Invoice') ?>
            </a>
            <?php
        }

        public function _addInvoiceButton($order){
            ?>
            <a class="btn btn-outline-dark btn-sm mt-1 w-100" href="<?php echo esc_url(url('booking/invoice/' . $order['order_token'])) ?>" target="_blank"><?php echo __('Invoice') ?></a>
            <?php
        }

        public function _addCompleteOrderLink($order){
            ?>
            <a href="<?php echo esc_url(url('booking/invoice/' . $order['order_token'])) ?>" class="text-primary pl-4" target="_blank"><?php echo __('Invoice'); ?></a>
            <?php
        }

        public function _addScriptEditService(){
            admin_enqueue_styles('invoice-css');
            admin_enqueue_scripts('invoice-js');
        }

        public function enqueueScripts(){
            \App\Modules\Backend\Controllers\ScriptController::inst()->_addStyle('ical-css', asset('plugins/Invoice/Assets/css/main.css'));
            \App\Modules\Backend\Controllers\ScriptController::inst()->_addScript('ical-js', asset('plugins/Invoice/Assets/js/main.js'));
        }

        public static function inst()
        {
            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }
            return self::$_inst;
        }
    }

    InvoiceCore::inst();
}