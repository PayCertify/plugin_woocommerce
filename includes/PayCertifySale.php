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


    $exp_date = explode("/", sanitize_text_field($_POST['paycertify-card-expiry']));
    $exp_month = str_replace(' ', '', $exp_date[0]);
    $exp_year = str_replace(' ', '', $exp_date[1]);

    if (strlen($exp_year) == 2) {
        $exp_year += 2000;
    }


    var_dump($exp_month);

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

        return $fields;

}
add_filter( 'woocommerce_checkout_fields', 'add_field_custom_attr' );


function custom_credit_card_fields_golf_cc ($cc_fields , $payment_id){

    $cc_fields = array(
     // 'card-type' => '<p class="form-row form-row-wide">
     // <label for="' . esc_attr( $payment_id) . '-card-type">' . __( 'Credit Card Type', 'woocommerce' ) . ' <span class="required">*</span></label>
     // <select class="wc-credit-card-form-card-type" name="' . ( $args['fields_have_names'] ? $payment_id . '-card-number' : '' ) . '" id="' . esc_attr( $payment_id ) . '-card-number">
     // <option value="Visa">Visa</option>
     // <option value="MasterCard">Master Card</option>
     // <option value="Discover">Discover</option>
     // <option value="American Express">American Express</option> 
     // </select>
     // </p>',

    '<script>
        jQuery("#paycertify-card-expiry").on("change paste keyup", function() {
           alert($(this).val()); 
        });
    </script>',

     'card-number-field' => '<p class="form-row form-row-wide">
     <label for="' . esc_attr( $payment_id ) . '-card-number">' . __( 'Card Number', 'woocommerce' ) . ' <span class="required">*</span></label>
     <input id="' . esc_attr( $payment_id ) . '-card-number" class="input-text wc-credit-card-form-card-number" data-paycertify="card-number" type="text" maxlength="20" autocomplete="off" placeholder="•••• •••• •••• ••••" name="' . esc_attr( $payment_id ) . '-card-number" />
     </p>',
     'card-expiry-field' => '<p class="form-row form-row-first">
     <label for="' . esc_attr( $payment_id ) . '-card-expiry">' . __( 'Expiry (MM/YY)', 'woocommerce' ) . ' <span class="required">*</span></label>





     <input id="' . esc_attr( $payment_id ) . '-card-expiry" class="input-text wc-credit-card-form-card-expiry" data-paycertify="card-expiry-month" type="text" autocomplete="off" placeholder="' . __( 'MM / YY', 'woocommerce' ) . '" name="' . esc_attr( $payment_id ) . '-card-expiry" />

        
     <input id="' . esc_attr( $payment_id ) . '-expiry-year" data-paycertify="card-expiry-year" type="text" />
     






     </p>',
     'card-cvc-field' => '<p class="form-row form-row-last">
     <label for="' . esc_attr( $payment_id ) . '-card-cvc">' . __( 'Card Code', 'woocommerce' ) . ' <span class="required">*</span></label>
     <input id="' . esc_attr( $payment_id ) . '-card-cvc" class="input-text wc-credit-card-form-card-cvc" data-paycertify="card-cvv" type="text" autocomplete="off" placeholder="' . __( 'CVC', 'woocommerce' ) . '" name="' . esc_attr( $payment_id ) . '-card-cvc" />
     </p>'
    );


    return $cc_fields;

}
add_filter( 'woocommerce_credit_card_form_fields' , 'custom_credit_card_fields_golf_cc' , 10, 2 );

