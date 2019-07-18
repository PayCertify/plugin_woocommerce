<?php

// ADD SCRIPT PayCertify.js
function PayCertifyJs( ) {  

    //CLASS WC_PayCertify token admin
    $obj = new WC_PayCertify;
    $api_token = $obj->settings['api_token'];

    wp_enqueue_script('paycertify-js', 'https://js.paycertify.com/paycertify.min.js?key='.$api_token.'&mode=test', array('jquery'), '0.1', true );

}
add_action( 'wp_enqueue_scripts', 'PayCertifyJs' );



add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_hidden_field', 10, 1 );
function my_custom_checkout_hidden_field( $wc_order ) {
    

    var_dump($wc_order);

    // Output the hidden link
    echo '<div id="PayCertifyChekout">
            <input type="hidden" type="hidden" data-paycertify="processor_id"/> 
            <input type="hidden" type="hidden" data-paycertify="amount" value="1"/>
            <input type="hidden" type="text" data-paycertify="first-name"/>
            <input type="hidden" type="text" data-paycertify="last-name"/>
            <input type="hidden" type="text" data-paycertify="email"/>
            <input type="hidden" type="text" data-paycertify="phone"/>
            <input type="hidden" type="text" data-paycertify="address-l1"/>
            <input type="hidden" type="text" data-paycertify="address-l2"/>
            <input type="hidden" type="text" data-paycertify="city"/>
            <input type="hidden" type="text" data-paycertify="state"/>
            <input type="hidden" type="text" data-paycertify="country"/>
            <input type="hidden" type="text" data-paycertify="zip"/>
            <input type="hidden" type="text" data-paycertify="card-number"/>
            <input type="hidden" type="text" data-paycertify="card-expiry-month"/>
            <input type="hidden" type="text" data-paycertify="card-expiry-year"/>
            <input type="hidden" type="text" data-paycertify="card-cvv"/>
    </div>';
}


?>
