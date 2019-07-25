<?php

// ADD SCRIPT PayCertify.js
function PayCertifyJs( ) {  

    //CLASS WC_PayCertify token admin
    $obj = new WC_PayCertify;
    $api_token = $obj->settings['api_token'];

    if( is_checkout() ){
        wp_enqueue_script('paycertify-js', 'https://js.paycertify.com/paycertify.min.js?key='.$api_token.'', array('jquery'), '0.1', true );
    }
}
add_action( 'wp_enqueue_scripts', 'PayCertifyJs' );


function my_custom_checkout_hidden_field( $order_id ) {

    global $woocommerce;
    $obj = new WC_PayCertify;
    
    $cart_amount = $woocommerce->cart->total;
    $processor_id = $obj->settings['processor_id'];

    // Output the hidden link
    echo '<div id="PayCertifyChekout">
            <input type="hidden" data-paycertify="processor_id" value="'.$processor_id.'"/> 
            <input type="hidden" data-paycertify="amount" value="'.$cart_amount.'"/>
    </div>';

}
add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_hidden_field', 10, 1 );


function add_field_custom_attr( $fields ) {

        // Adding data-paycertify attribute to billing fields.
        $fields['billing']['billing_first_name']['custom_attributes'] = array(
            'data-paycertify' => 'first-name'
            );
        $fields['billing']['billing_last_name']['custom_attributes'] = array(
            'data-paycertify' => 'last-name'
            );
        $fields['billing']['billing_email']['custom_attributes'] = array(
            'data-paycertify' => 'email'
            );
        $fields['billing']['billing_phone']['custom_attributes'] = array(
            'data-paycertify' => 'phone'
            );
        $fields['billing']['billing_country']['custom_attributes'] = array(
            'data-paycertify' => 'country'
            );
        $fields['billing']['billing_address_1']['custom_attributes'] = array(
            'data-paycertify' => 'address-l1'
            );
        $fields['billing']['billing_address_2']['custom_attributes'] = array(
            'data-paycertify' => 'address-l2'
            );
        $fields['billing']['billing_city']['custom_attributes'] = array(
            'data-paycertify' => 'city'
            );
        $fields['billing']['billing_state']['custom_attributes'] = array(
            'data-paycertify' => 'state'
            );
        $fields['billing']['billing_postcode']['custom_attributes'] = array(
            'data-paycertify' => 'zip'
            );


        //CARD
         $fields['billing']['paycertify-card-number']['custom_attributes'] = array(
            'data-paycertify' => 'card-number'
            );

        return $fields;

}
add_filter( 'woocommerce_checkout_fields', 'add_field_custom_attr' );
