<?php

/*
 * Plugin Name: WooCommerce PayCertify Payment Gateway
 * Plugin URI: https://wordpress.org/plugins/woocommerce-gateway-paycertify
 * Description: Take credit card payments on your WooCommerce store using PayCertify.
 * Version: 2.5.3
 * Stable tag: 2.5.3
 * Author: PayCertify Developers Team
 * WC tested up to: 5.6
 * Text Domain: woocommerce-gateway-paycertify
 * Author URI: https://paycertify.com
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function woocommerce_paycertify_init()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    include(plugin_dir_path(__FILE__) . "paycertify.php");

    /**
     * Add the Gateway to WooCommerce
     */
    function woocommerce_add_paycertify_gateway($methods)
    {
        $methods[] = 'WC_PayCertify';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_paycertify_gateway');


    /**
     * Adds a privacy policy statement
     */
    function woocommerce_paycertify_add_privacy_policy_content()
    {
        if (!function_exists('wp_add_privacy_policy_content')) {
            return;
        }
        $content = '<p class="privacy-policy-tutorial">' . __('Some introductory content for the suggested text.', 'text-domain') . '</p>'
            . '<strong class="privacy-policy-tutorial">' . __('Suggested Text:', 'my_plugin_textdomain') . '</strong> '
            . sprintf(
                __('We use Your Personal data to provide and improve the Service. By using the Service, You agree to the collection and use of your information, as well as any other terms, as outlined in this <a href="%1$s" target="_blank">Privacy Policy</a>.', 'text-domain'),
                'https://www.paycertify.com/privacy-policy/'
            );
        wp_add_privacy_policy_content('WooCommerce PayCertify Payment Gateway', wp_kses_post(wpautop($content, false)));
    }

    add_action('admin_init', 'woocommerce_paycertify_add_privacy_policy_content');


    function paycertify_woocommerce_settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=paycertify">' . __('Settings') . '</a>';
        array_push($links, $settings_link);
        return $links;
    }

    $plugin = plugin_basename(__FILE__);

    add_filter("plugin_action_links_$plugin", 'paycertify_woocommerce_settings_link');
}

add_action('plugins_loaded', 'woocommerce_paycertify_init', 0);

?>
