<?php

// ADD SCRIPT PayCertify.js
function PayCertifyJs( ) {  

    //CLASS WC_PayCertify token admin
    $obj = new WC_PayCertify;
    $api_token = $obj->settings['api_token'];
    $mode = $obj->settings['test_mode_enabled'];

    if( $mode == 'yes' ){
        $mode_return = '&mode=test';
    }

    if( is_checkout() && $api_token ){
        wp_enqueue_script('paycertify-js', 'https://js.paycertify.com/paycertify.min.js?key='.$api_token.''.$mode_return.'', array('jquery'), '0.1', true );
    }

}
add_action( 'wp_enqueue_scripts', 'PayCertifyJs' );


function my_custom_checkout_hidden_field( $wc_order ) {
    
    $exp_date = explode("/", sanitize_text_field($_POST['paycertify-card-expiry']));
    $exp_month = str_replace(' ', '', $exp_date[0]);
    $exp_year = str_replace(' ', '', $exp_date[1]);

    if (strlen($exp_year) == 2) {
        $exp_year += 2000;
    }

    var_dump($wc_order->order_total);

    // Output the hidden link
    echo '<div id="PayCertifyChekout">
            <input type="hidden" data-paycertify="processor_id" value="'.$wc_order->processor_id.'"/> 
            <input type="hidden" data-paycertify="amount" value="'.$wc_order->order_total.'"/>
            <input type="hidden" type="text" data-paycertify="first-name" value="'.$wc_order->billing_first_name.'"/>
            <input type="hidden" type="text" data-paycertify="last-name" value="'.$wc_order->billing_last_name.'"/>
            <input type="hidden" type="text" data-paycertify="email" value="'.$wc_order->billing_email.'"/>
            <input type="hidden" type="text" data-paycertify="phone" value="'.$wc_order->order_total.'"/>
            <input type="hidden" type="text" data-paycertify="address-l1" value="'.$wc_order->billing_address_1.'"/>
            <input type="hidden" type="text" data-paycertify="address-l2" value="'.$wc_order->billing_address_2.'"/>
            <input type="hidden" type="text" data-paycertify="city" value="'.$wc_order->billing_city.'"/>
            <input type="hidden" type="text" data-paycertify="state" value="'.$wc_order->billing_state.'"/>
            <input type="hidden" type="text" data-paycertify="country" value="'.$wc_order->billing_country.'"/>
            <input type="hidden" type="text" data-paycertify="zip" value="'.$wc_order->billing_postcode.'"/>
            <input type="hidden" type="text" data-paycertify="card-number" value="'.str_replace(array(' ', '-'), '', sanitize_text_field($_POST['paycertify-card-number'])).'"/>
            <input type="hidden" type="text" data-paycertify="card-expiry-month" value="'.$exp_month.'"/>
            <input type="hidden" type="text" data-paycertify="card-expiry-year" value="'.$exp_year.'"/>
            <input type="hidden" type="text" data-paycertify="card-cvv" value="'.sanitize_text_field($_POST['paycertify-card-cvc']).'"/>
    </div>';
}
add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_hidden_field', 10, 1 );


?>
