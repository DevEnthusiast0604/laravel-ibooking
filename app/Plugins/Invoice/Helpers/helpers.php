<?php
if(!function_exists('get_invoice_logo')){
    function get_invoice_logo(){
        $logo = '';
        $option = get_option('invoice_logo');
        if(empty($option)){
            $logo = get_logo();
        }else{
            $url = get_attachment_url( $option );
            if ( ! empty( $url ) ) {
                $logo = $url;
            }
        }
        return $logo;
    }
}

if(!function_exists('get_invoice_company_name')){
    function get_invoice_company_name(){
        $name = '';
        $option = get_option('invoice_name');
        if(empty($option)){
            $option = get_option('site_name');
        }
        if(!empty($option)){
            $name = get_translate($option);
        }
        return $name;
    }
}

if(!function_exists('get_invoice_address')){
    function get_invoice_address(){
        $option = get_option('invoice_address');
        return get_translate($option);
    }
}
