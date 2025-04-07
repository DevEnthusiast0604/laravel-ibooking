<?php
if(!class_exists('GMZ_Mail')) {
    class GMZ_Mail
    {
        private static $_inst;

        public function sendEmailPartnerApproved($user_id)
        {
            $site_name = get_translate(get_option('site_name'));

            $subject = sprintf(__('[%s] Account Approved'), $site_name);
            $body = view('Frontend::emails.partner.account-approved', [
                'user_id' => $user_id
            ])->render();

            send_email(get_user_email($user_id), $subject, $body);
        }

        public function sendEmailPartnerRequest($post_data)
        {
            $user_admin = get_user_data(get_option('admin_user'));
            $site_name = get_translate(get_option('site_name'));

            $subject = sprintf(__('[%s] Partner Registration'), $site_name);
            $body = view('Frontend::emails.admin.partner-registration', [
                'user' => $user_admin,
                'post_data' => $post_data,
            ])->render();

            send_email($user_admin['email'], $subject, $body);
        }


        public static function inst()
        {
            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }
            return self::$_inst;
        }
    }
}