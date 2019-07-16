<?php

class WC_PayCertify extends WC_Payment_Gateway {

	// const WC_ORDER_ID = 'woocommerce_order_id';
 //    const API_TOKEN = '';

    protected $visibleSettings = array(
        'enabled',
        'title',
        'description',
        'api_token',
        'avs_enabled',
        'partial_refund',
        'dynamic_descriptor',
        'processor_id',
        'test_mode_enabled',
    );
    public $form_fields = array();

    /**
     * Unique ID for the gateway
     * @var string
     */
    public $id = 'paycertify';

    /**
     * Title of the payment method shown on the admin page.
     * @var string
     */
    public $method_title = 'PayCertify';

    /**
     * Icon URL, set in constructor
     * @var string
     */
    public $icon;

    /**
     * Return Wordpress plugin settings
     * @param  string $key setting key
     * @return mixed setting value
     */
    public function getSetting($key) {
        return $this->settings[$key];
    }

    /**
     * @param boolean $hooks Whether or not to
     * setup the hooks on calling the constructor
     */
    public function __construct() {

        // The global ID for this Payment method
        $this->id = "paycertify";

        // The Title shown on the top of the Payment Gateways Page next to all the other Payment Gateways
        $this->method_title = __("PayCertify Gateway", 'paycertify');

        // The description for this Payment Gateway, shown on the actual Payment options page on the backend
        $this->method_description = __("Pay securely through PayCertify.", 'paycertify');

        // The title to be used for the vertical tabs that can be ordered top to bottom
        $this->title = __($this->get_title(), 'paycertify');

        $this->icon = null;

        // Bool. Can be set to true if you want payment fields to show on the checkout 
        // if doing a direct integration, which we are doing in this case
        $this->has_fields = true;

        // Supports the default credit card form
        $this->supports = array('default_credit_card_form', 'products', 'refunds');

        $this->init_form_fields();

        $this->init_settings();

        add_action('admin_notices', array($this, 'do_ssl_check'));

        // Save settings
        if (is_admin()) {
            // Save our administration options.
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        }
    }


    public function init_form_fields() {

        $formfields = array(
            'enabled' => array(
                'title' => __('Enable / Disable', $this->id),
                'label' => __('Enable PayCertify gateway ?', $this->id),
                'type' => 'checkbox',
                'default' => 'no',
            ),
            'title' => array(
                'title' => __('Title', $this->id),
                'type' => 'text',
                'desc_tip' => __('Payment title the customer will see during the checkout process.', $this->id),
                'default' => __('PayCertify Gateway', $this->id),
            ),
            'description' => array(
                'title' => __('Description', $this->id),
                'type' => 'textarea',
                'desc_tip' => __('Payment description the customer will see during the checkout process.', $this->id),
                'default' => __('Pay securely through PayCertify.', $this->id),
                'css' => 'max-width:350px;'
            ),
            'api_token' => array(
                'title' => __('API Token', $this->id),
                'type' => 'text',
                'desc_tip' => __('PayCertify API Token.', $this->id),
            ),
            'processor_id' => array(
                'title' => __('Processor ID', $this->id),
                'type' => 'text',
                'desc_tip' => __('The ID of the Processor.', $this->id),
            ),
            'avs_enabled' => array(
                'title' => __('Enable AVS', $this->id),
                'label' => __('Enable AVS', $this->id),
                'type' => 'checkbox',
                'desc_tip' => __('Address Verification Service.', $this->id),
                'default' => 'no',
            ),
            'partial_refund' => array(
                'title' => __('Enable Partial Refunds', $this->id),
                'label' => __('Enable Partial Refunds', $this->id),
                'type' => 'checkbox',
                'desc_tip' => __('Check this box to allow Partial Refunds.', $this->id),
                'default' => 'no',
            ),
            'dynamic_descriptor' => array(
                'title' => __('Dynamic Descriptor', $this->id),
                'type' => 'text',
                'desc_tip' => __('The credit card statement descriptor.', $this->id),
            ),
            'test_mode_enabled' => array(
                'title' => __('Enable Test Mode', $this->id),
                'label' => __('Enable Test Mode', $this->id),
                'type' => 'checkbox',
                'default' => 'yes',
            ),
        );

        foreach ($formfields as $key => $value) {
            if (in_array($key, $this->visibleSettings, true)) {
                $this->form_fields[$key] = $value;
            }
        }
    }


    //VALIDATE FIELDS CARD
    public function validate_fields() {
        global $woocommerce;

        if (!$this->is_empty_credit_card($_POST[esc_attr($this->id) . '-card-number'])) {
            wc_add_notice('<strong>Credit Card Number</strong> ' . __('is a required field.', 'paycertify'), 'error');
        }

        if (!$this->is_empty_expire_date($_POST[esc_attr($this->id) . '-card-expiry'])) {
            wc_add_notice('<strong>Card Expiry Date</strong> ' . __('is a required field.', 'paycertify'), 'error');
        }

        if (!$this->is_empty_ccv_number($_POST[esc_attr($this->id) . '-card-cvc'])) {
            wc_add_notice('<strong>CCV Number</strong> ' . __('is a required field.', 'paycertify'), 'error');
        }
    }

    private function is_empty_credit_card($credit_card) {

        if (empty($credit_card))
            return false;

        return true;
    }

    private function is_empty_expire_date($ccexp_expiry) {

        $ccexp_expiry = str_replace(' / ', '', $ccexp_expiry);

        if (is_numeric($ccexp_expiry) && ( strlen($ccexp_expiry) == 4 )) {
            return true;
        }

        return false;
    }

    private function is_empty_ccv_number($ccv_number) {

        $length = strlen($ccv_number);

        return is_numeric($ccv_number) AND $length > 2 AND $length < 5;
    }
   
}
