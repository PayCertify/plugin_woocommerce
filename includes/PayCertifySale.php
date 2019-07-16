<?php

public function PayCertifyJs($key, $mode){

    wp_enqueue_script('paycertify-js', 'https://js.paycertify.com/paycertify.min.js?key=064BDCCB1F7A8835A468081753A633CA0B679FC8&mode=test', array('jquery'), '0.1', true );

    //FIELD EDIT WOO FORM CHECKOUT
    function kia_filter_checkout_fields($fields){
        
    }
add_filter( 'woocommerce_checkout_fields', 'kia_filter_checkout_fields' );

}

?>
