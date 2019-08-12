<?php


// ADD SCRIPT PayCertify.js
function wc_paycertify_scripts( ) {  

    //CLASS WC_PayCertify token admin
    $obj = new WC_PayCertify;
    $api_token = $obj->settings['api_token'];

    if( is_checkout() && !is_order_received_page() ){
        wp_enqueue_script('paycertify-js', 'https://js.paycertify.com/paycertify.min.js?key='.$api_token.'', array('jquery'), '2.0', true );
        wp_enqueue_style('paycertify-css', plugins_url() . '/woo-paycertify/assets/css/paycertify.min.css');
    }
}
add_action( 'wp_enqueue_scripts', 'wc_paycertify_scripts' );


function wc_paycertify_checkout_hidden_field() {

    global $woocommerce, $post;
    $obj = new WC_PayCertify;
    
    $cart_amount = $woocommerce->cart->total;
    $processor_id = $obj->settings['processor_id'];

    // Output the hidden link
    echo '<div id="PayCertifyChekout">
            <input type="hidden" data-paycertify="processor_id" value="'.$processor_id.'"/> 
            <input type="hidden" data-paycertify="amount" value="'.$cart_amount.'"/>
    </div>';

    if($_SESSION["order_id_session"]){
        $order_id_session = $_SESSION["order_id_session"];
        $order = new WC_Order( $order_id_session );
        if( $_POST['transaction']['events'][0]['success'] == 'true' ){
            $order->update_status( 'completed' );
            WC()->cart->empty_cart(true);
            wp_redirect( site_url(). '/checkout/order-received/'.$order_id_session.'/?key='.$order->order_key.'' );
            session_destroy();
        }else{
            $order->update_status( 'failed' );
            wc_add_notice("We weren't able to process this card. Please contact your bank for more information.", $notice_type = 'error');
        }
    }

}
add_action( 'woocommerce_after_order_notes', 'wc_paycertify_checkout_hidden_field', 10, 1 );


function wc_paycertify_add_field_custom_attr( $fields ) {

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
add_filter( 'woocommerce_checkout_fields', 'wc_paycertify_add_field_custom_attr' );


function wc_paycertify_credit_card_fields($cc_fields , $payment_id){

    $cc_fields = array(

     'card-number-field' => '<p class="form-row form-row-wide">
        <label for="' . esc_attr( $payment_id ) . '-card-number">' . __( 'Card Number', 'woocommerce' ) . ' <span class="required">*</span></label>
        <input id="' . esc_attr( $payment_id ) . '-card-number" class="input-text wc-credit-card-form-card-number" data-paycertify="card-number" type="text" autocomplete="off" placeholder="•••• •••• •••• ••••" name="' . esc_attr( $payment_id ) . '-card-number" />
     </p>',


     'card-expiry-field' => '<p class="form-row form-row-first">
        <label for="' . esc_attr( $payment_id ) . '-card-expiry">' . __( 'Expiry ( MM )', 'woocommerce' ) . ' <span class="required">*</span></label>
        
        <select data-paycertify="card-expiry-month" id="' . esc_attr( $payment_id ) . '-card-expiry" name="' . esc_attr( $payment_id ) . '-card-expiry" id="' . esc_attr( $payment_id ) . '-card-number">
            <option>01</option>
            <option>02</option>
            <option>03</option>
            <option>04</option>
            <option>05</option>
            <option>06</option>
            <option>07</option>
            <option>08</option>
            <option>09</option>
            <option>10</option>
            <option>11</option>
            <option>12</option>
         </select>

     </p>',


     'card-year-field' => '<p class="form-row form-row-last">
        <label for="' . esc_attr( $payment_id ) . '-card-year">' . __( 'Year ( YY )', 'woocommerce' ) . ' <span class="required">*</span></label>

        <select id="' . esc_attr( $payment_id ) . '-card-year" data-paycertify="card-expiry-year" name="' . esc_attr( $payment_id ) . '-card-year" id="' . esc_attr( $payment_id ) . '-card-number">
            <option>19</option>
            <option>20</option>
            <option>21</option>
            <option>22</option>
            <option>23</option>
            <option>24</option>
            <option>25</option>
            <option>26</option>
            <option>27</option>
            <option>28</option>
            <option>29</option>
         </select>

     </p>',


     'card-cvc-field' => '<p class="form-row form-row-last">
        <label for="' . esc_attr( $payment_id ) . '-card-cvc">' . __( 'Card Code', 'woocommerce' ) . ' <span class="required">*</span></label>
        <input id="' . esc_attr( $payment_id ) . '-card-cvc" class="input-text wc-credit-card-form-card-cvc" data-paycertify="card-cvv" type="text" autocomplete="off" placeholder="' . __( 'CVC', 'woocommerce' ) . '" name="' . esc_attr( $payment_id ) . '-card-cvc" />
     </p>'


    );

    return $cc_fields;

}
add_filter( 'woocommerce_credit_card_form_fields' , 'wc_paycertify_credit_card_fields' , 10, 2 );