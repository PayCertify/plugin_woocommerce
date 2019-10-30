===  PayCertify WooCommerce Plugin ===
Contributors: PayCertify Engineering Team
Tags: PayCertify Payment Gateway, PayCertify, PayCertify woocommerce plugin, 3ds, 3d secure, kount, fraud prevention, visa, mastercard, amex
Requires at least: 4.7.5
Tested up to: 5.2.3
Stable tag: 2.0.3

License: GPLv2 or later

== Description ==

Accept Visa, MasterCard, American Express, Discover, JCB, Diners Club and more directly on your store with the PayCertify Payment Gateway for WooCommerce.

== Screenshots ==

1. The settings panel used to configure the gateway.

== Frequently Asked Questions ==

- Where do I get the credentials to use the PayCertify Gateway and Fraud Tools?
Go to https://paycertify.com and signup to an account. Once you are done, you will get your sandbox credentials. To get a production-ready credential you need to go through our document submission process.

- How can I generate my publishable key?
In order to generate a publishable key, you will need a PayCertify account. After signing in with a merchant log in with admin privileges, you should be able to navigate to the “Settings” page of your account. Once you’re there, search for the “Publishable Keys” section and click “Add new”. After clicking on it, a right panel will pop up and there you should be able to fill the allowed origins, the mode and an URL to send over your users whenever one of your fraud checks fail. Learn more: https://paycertify.com/docs/paycertifyjs/generate-publishable-key


== Changelog ==

= 2.0.3 - 2019-10-29 =
* Fixed a bug on Payment Method Title and Desc.

= 2.0.2 - 2019-10-29 =
* Added cards icons

= 2.0.1 - 2019-10-25 =
* Fixed a bug on apply discount.

= 2.0.0 - 2019-09-09 =
* This version has breaking changes, update carefully!
* Added support to Kount
* Added support to 3D Secure
* Removed the AVS support (Breaking Changes)
* Removed the Partial Refund support (Breaking Changes)

= 1.1.1 - 2019-08-06 =
* Test new version 5.2.2

= 1.1.0 - 2018-10-12 =
* Added option to add `processor_id`
* Added test mode feature
* Fixed 500 error occurring sometimes

= 1.0.0 - 2018-04-24 =
* Created first stable version of the PayCertify WooCommerce Plugin.
