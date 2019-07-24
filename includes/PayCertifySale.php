<?php
    
wp_enqueue_script('paycertify-js', 'https://js.paycertify.com/paycertify.min.js?key=064BDCCB1F7A8835A468081753A633CA0B679FC8&mode=test', array('jquery'), '0.1', true );


function kia_filter_checkout_fields($fields){
    $fields['billing_first_name'] = array(
            'some_field' => array(
                'type' => 'text',
                'required'      => true,
                'label' => __( 'Some field' )
                ),
            'another_field' => array(
                'type' => 'select',
                'options' => array( 'a' => __( 'apple' ), 'b' => __( 'bacon' ), 'c' => __( 'chocolate' ) ),
                'required'      => true,
                'label' => __( 'Another field' )
                )
            );
 
    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'kia_filter_checkout_fields' );



?>
