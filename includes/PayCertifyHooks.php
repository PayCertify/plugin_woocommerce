<?php

// Start session id order
session_start();

/**
 * WC Function PayCertify Scripts
 */
function wc_paycertify_scripts()
{
    $obj = new WC_PayCertify;
    $api_token = sanitize_text_field($obj->settings['api_token']);

    if (is_checkout() && !is_order_received_page() && !is_admin()) {
        wp_enqueue_script('paycertify-js', 'https://js.paycertify.com/staging/paycertify.min.js?key=' . $api_token . '', array(), '2.0', true);
        wp_enqueue_style('paycertify-css', plugin_dir_url(__DIR__) . '/assets/css/paycertify.min.css');
    }
}

add_action('wp_enqueue_scripts', 'wc_paycertify_scripts');


function total_price_cypher_update_checkout()
{
    if (!is_checkout())
        return;

    $obj = new WC_PayCertify;
    ?>
    <script type="text/javascript">
        window.onload = () => {
            const parentDiv = document.getElementById('PayCertifyCheckout');

            if(parentDiv) {
                const config = {attributes: true, childList: true, subtree: false};
                let amountInput = parentDiv.querySelector('input[data-paycertify="amount"]');
                let amountBlock = null;
                let psLabel = null;
                let psMethod = null;

                const callback = function (mutationsList, observer) {
                    // Use traditional 'for loops' for IE 11
                    for (let mutation of mutationsList) {
                        // On Amount change, re-generate cypher
                        amountBlock = document.querySelector('.order-total .woocommerce-Price-amount');
                        if(amountInput.value !== amountBlock.textContent.replace(/[^0-9.]+/g, "")) {
                            amountInput.value = amountBlock.textContent.replace(/[^0-9.]+/g, "");

                            psLabel = document.querySelector(".payment_method_paycertify label");
                            if (psLabel && psLabel.childNodes.length) {
                                psLabel.childNodes[0].textContent = "<?php echo sanitize_text_field($obj->settings['title']) ?>";
                            }
                            psMethod = document.querySelector(".payment_box.payment_method_paycertify p");
                            if (psMethod && psMethod.childNodes.length) {
                                psMethod.childNodes[0].textContent = "<?php echo sanitize_text_field($obj->settings['description']) ?>";
                            }

                            if (window.pcjs && window.pcjs.PayButton && amountInput.value) {
                                const txid = window.pcjs.PayButton.memoized.merchant_transaction_id;

                                if (txid) {
                                    window.pcjs.PayButton.txCypher({merchant_transaction_id: txid, amount: amountInput.value})
                                        .then((cc) => {
                                            window.pcjs.PayButton.cypher = cc.token;
                                        });
                                }
                            }
                        }
                    }
                };

                const pcjs_amount_observer = new MutationObserver(callback);
                pcjs_amount_observer.observe(parentDiv, config);
            }

            jQuery(function($){
                $('form.woocommerce-checkout').on( 'change', 'select#billing_country', function(){

                    if (window.pcjs && window.pcjs.ThreeDSecure) {
                        setTimeout(() => {
                            const stateInput = document.querySelector('#billing_state');
                            if(stateInput) {
                                stateInput.dataset.paycertify = 'state';
                            }
                            window.pcjs.ThreeDSecure.countryChanged();
                        });
                    }
                });
            });

            jQuery(function($){
                $('form.woocommerce-checkout').on( 'change', 'select#billing_state', function(){
                    if (window.pcjs && window.pcjs.ThreeDSecure) {
                        window.pcjs.ThreeDSecure.stateChanged();
                    }
                });
            });
        };

    </script>
    <?php
}

add_action('wp_footer', 'total_price_cypher_update_checkout', 50);

/**
 * WC Function PayCertify Checkout Fields and Transaction
 */
function wc_paycertify_checkout_hidden_field()
{
    global $woocommerce, $post;
    $cart_amount = $woocommerce->cart->total;

    echo '<div id="PayCertifyCheckout">
        <input type="hidden" data-paycertify="amount" value="' . $cart_amount . '"/>
    </div>';

    if (!isset($_SESSION) || !isset($_SESSION["order_id_session"]))
        return;

    $order_id_session = sanitize_text_field($_SESSION["order_id_session"]);
    $order = new WC_Order($order_id_session);

    if (!isset($_POST['transaction']) || !isset($_POST['transaction']['events']) || !isset($_POST['transaction']['events'][0])
        || !isset($_POST['transaction']['events'][0]['success'])) {

        $order->update_status('failed');
        wc_add_notice("We weren't able to process this card. <strong>Unknown server error.</strong>", $notice_type = 'error');
        return;
    }

    if ($_POST['transaction']['events'][0]['success'] !== 'true') {

        $message = isset($_POST['transaction']['events'][0]['processor_message'])
            ? sanitize_text_field($_POST['transaction']['events'][0]['processor_message'])
            : "Unknown server error.";
        $order->update_status('failed');
        wc_add_notice("We weren't able to process this card. <strong>$message</strong>", $notice_type = 'error');
        return;
    }

    // Update status processing
    $order->update_status('completed');

    // The text for the note
    $note = __('Payment processed on ' . date("d-M-Y h:i:s e"));

    // Add the note
    $order->add_order_note($note);

    // Empty cart
    WC()->cart->empty_cart(true);

    // Redirect Payment
    wp_redirect(wc_get_endpoint_url('order-received') . '/' . $order_id_session . '/?key=' . $order->order_key . '');

    // Session Destroy
    session_destroy();
}

add_action('woocommerce_after_order_notes', 'wc_paycertify_checkout_hidden_field', 10, 1);

/**
 * WC Function PayCertify add Field attr
 */
function wc_paycertify_add_field_custom_attr($fields)
{
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

add_filter('woocommerce_checkout_fields', 'wc_paycertify_add_field_custom_attr');

/**
 * WC Function Paycertify credit card custom fields
 */
function wc_paycertify_credit_card_fields($cc_fields, $payment_id)
{
    $cc_fields = array(
        'card-number-field' => '<p class="form-row form-row-wide">
        <label for="' . esc_attr($payment_id) . '-card-number">' . __('Card Number', 'woocommerce') . ' <span class="required">*</span></label>
        <input id="' . esc_attr($payment_id) . '-card-number" class="input-text wc-credit-card-form-card-number" data-paycertify="card-number" type="text" autocomplete="off" placeholder="•••• •••• •••• ••••" name="' . esc_attr($payment_id) . '-card-number" />
     </p>',

        'card-expiry-field' => '<p class="form-row form-row-first">
        <label for="' . esc_attr($payment_id) . '-card-expiry">' . __('Expiry ( MM )', 'woocommerce') . ' <span class="required">*</span></label>

        <select data-paycertify="card-expiry-month" id="' . esc_attr($payment_id) . '-card-expiry" name="' . esc_attr($payment_id) . '-card-expiry" id="' . esc_attr($payment_id) . '-card-number">
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
        <label for="' . esc_attr($payment_id) . '-card-year">' . __('Year ( YY )', 'woocommerce') . ' <span class="required">*</span></label>

        <select id="' . esc_attr($payment_id) . '-card-year" data-paycertify="card-expiry-year" name="' . esc_attr($payment_id) . '-card-year" id="' . esc_attr($payment_id) . '-card-number">
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
        <label for="' . esc_attr($payment_id) . '-card-cvc">' . __('Card Code', 'woocommerce') . ' <span class="required">*</span></label>
        <input id="' . esc_attr($payment_id) . '-card-cvc" class="input-text wc-credit-card-form-card-cvc" data-paycertify="card-cvv" type="text" autocomplete="off" placeholder="' . __('CVC', 'woocommerce') . '" name="' . esc_attr($payment_id) . '-card-cvc" />
     </p>'
    );

    return $cc_fields;
}

add_filter('woocommerce_credit_card_form_fields', 'wc_paycertify_credit_card_fields', 10, 2);


function custom_woocommerce_icons()
{
    if (!is_checkout())
        return;

    $icon = '<img class="paycertify-icon" src="' . plugin_dir_url(__DIR__) . 'assets/img/visa.svg' . '" alt="Visa" />';
    $icon .= '<img class="paycertify-icon" src="' . plugin_dir_url(__DIR__) . 'assets/img/mastercard.svg' . '" alt="Mastercard" />';
    $icon .= '<img class="paycertify-icon" src="' . plugin_dir_url(__DIR__) . 'assets/img/amex.svg' . '" alt="American Express" />';
    $icon .= '<img class="paycertify-icon" src="' . plugin_dir_url(__DIR__) . 'assets/img/discover.svg' . '" alt="Visa" />';
    $icon .= '<img class="paycertify-icon" src="' . plugin_dir_url(__DIR__) . 'assets/img/jcb.svg' . '" alt="JCB" />';
    $icon .= '<img class="paycertify-icon" src="' . plugin_dir_url(__DIR__) . 'assets/img/diners.svg' . '" alt="Diners Club" />';

    return $icon;
}

add_filter('woocommerce_gateway_icon', 'custom_woocommerce_icons');