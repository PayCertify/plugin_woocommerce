<?php

/*
 * Plugin Name: WooCommerce PayCertify Payment Gateway
 * Plugin URI: https://wordpress.org/plugins/woocommerce-gateway-paycertify
 * Description: Take credit card payments on your WooCommerce store using PayCertify.
 * Version: 2.0.3
 * Stable tag: 2.0.3
 * Author: Paycertify Developers Team
 * WC tested up to: 5.2.3
 * Text Domain: woocommerce-gateway-paycertify
 * Author URI: https://paycertify.com
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


add_action('plugins_loaded', 'woocommerce_paycertify_init', 0);

function woocommerce_paycertify_init() {
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    include(plugin_dir_path(__FILE__) . "paycertify.php");

    /**
     * Add the Gateway to WooCommerce
     * */
    function woocommerce_add_paycertify_gateway($methods) {
        $methods[] = 'WC_PayCertify';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_paycertify_gateway');

    function paycertify_woocommerce_settings_link($links) {

        $settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=paycertify">' . __('Settings') . '</a>';
        array_push($links, $settings_link);
        return $links;
    }

    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_$plugin", 'paycertify_woocommerce_settings_link');
}

?>
