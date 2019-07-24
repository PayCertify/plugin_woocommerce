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

    global $woocommerce;

    // echo '<pre>';
    // var_dump();
    // echo '</pre>';
    
    $exp_date = explode("/", sanitize_text_field($_POST['paycertify-card-expiry']));
    $exp_month = str_replace(' ', '', $exp_date[0]);
    $exp_year = str_replace(' ', '', $exp_date[1]);

    if (strlen($exp_year) == 2) {
        $exp_year += 2000;
    }

    $billing_first_name = $woocommerce->customer->billing['first_name'];
    $billing_last_name = $woocommerce->customer->billing['last_name'];
    $billing_address_1 = $woocommerce->customer->billing['address_1'];
    $billing_address_2 = $woocommerce->customer->billing['address_2'];
    $billing_city = $woocommerce->customer->billing['city'];
    $billing_postcode = $woocommerce->customer->billing['postcode'];
    $billing_country = $woocommerce->customer->billing['country'];
    $billing_state = $woocommerce->customer->billing['state'];
    $billing_email = $woocommerce->customer->billing['email'];
    $billing_phone = $woocommerce->customer->billing['phone'];

    // Output the hidden link
    echo '<div id="PayCertifyChekout">
            <input type="hidden" data-paycertify="processor_id" value="aacd4fd7-a118-41a9-be10-1c8aa1d99bfd"/> 
            <input type="hidden" data-paycertify="amount" value="100"/>
            <input type="hidden" type="text" data-paycertify="first-name" value="'.$billing_first_name.'"/>
            <input type="hidden" type="text" data-paycertify="last-name" value="'.$billing_last_name.'"/>
            <input type="hidden" type="text" data-paycertify="email" value="'.$billing_email.'"/>
            <input type="hidden" type="text" data-paycertify="phone" value="'.$billing_phone.'"/>
            <input type="hidden" type="text" data-paycertify="address-l1" value="'.$billing_address_1.'"/>
            <input type="hidden" type="text" data-paycertify="address-l2" value="'.$billing_address_2.'"/>
            <input type="hidden" type="text" data-paycertify="city" value="'.$billing_city.'"/>
            <input type="hidden" type="text" data-paycertify="state" value="'.$billing_state.'"/>
            <input type="hidden" type="text" data-paycertify="country" value="'.$billing_country.'"/>
            <input type="hidden" type="text" data-paycertify="zip" value="'.$billing_postcode.'"/>
            <input type="hidden" type="text" data-paycertify="card-number" value="'.str_replace(array(' ', '-'), '', sanitize_text_field($_POST['paycertify-card-number'])).'"/>
            <input type="hidden" type="text" data-paycertify="card-expiry-month" value="'.$exp_month.'"/>
            <input type="hidden" type="text" data-paycertify="card-expiry-year" value="'.$exp_year.'"/>
            <input type="hidden" type="text" data-paycertify="card-cvv" value="'.sanitize_text_field($_POST['paycertify-card-cvc']).'"/>
    </div>';
}
add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_hidden_field', 10, 1 );


?>
