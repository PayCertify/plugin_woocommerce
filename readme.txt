===  PayCertify WooCommerce Plugin ===
Contributors: PayCertify
Tags: PayCertify, WooCommerce, plugin, Payment Gateway, 3DS, 3D Secure, Kount, fraud prevention, Visa, MasterCard, amex
Requires at least: 4.7.5
Tested up to: 5.6
Requires PHP: 5.6
Stable tag: 2.5.3

License: GPLv2 or later

== Description ==

The PayCertify Checkout Payment Gateway allows you to process credit card payments through your WooCommerce store.

PayCertify Checkout comes with features that make the setup a breeze and ensure you have robust fraud prevention tools from the outset.

Your store will be able to process online payments and subscriptions, without being redirected to an externally hosted checkout page. This provides your customers with an intuitive and professional checkout experience. You are also protected with fraud prevention tools that can be used out-of-the-box, once your credentials have been configured.

The automatic installation process simplifies the setup steps, getting you up and running and processing payments in no time.

== Screenshots ==

1. The settings panel used to configure the gateway.

== Frequently Asked Questions ==

- Where do I get the credentials to use the PayCertify Gateway and Fraud Tools?
Go to https://paycertify.com and signup to an account. Once you are done, you will get your sandbox credentials. To get a production-ready credential you need to go through our document submission process.

- How can I generate my publishable key?
In order to generate a publishable key, you will need a PayCertify account. After signing in with a merchant log in with admin privileges, you should be able to navigate to the “Settings” page of your account. Once you’re there, search for the “Publishable Keys” section and click “Add new”. After clicking on it, a right panel will pop up and there you should be able to fill the allowed origins, the mode and an URL to send over your users whenever one of your fraud checks fail. Learn more: https://paycertify.com/docs/paycertifyjs/generate-publishable-key


== Changelog ==

= 2.5.3 - 2020-11-26 =
* 3D Secure v.2 support.

= 2.5.2 - 2020-11-20 =
* Security improvements.

= 2.5.1 - 2020-11-20 =
* Security improvements.

= 2.5.0 - 2020-11-05 =
* Security improvements.

= 2.4.0 - 2020-07-22 =
* UI improvements.
* Removed jQuery dependency.

= 2.1.1 - 2020-02-25 =
* Fixed a bug on Order Management Status.

= 2.1.0 - 2020-02-25 =
* Fixed a bug on Order Management Status.
* Fixed a bug on Payment Method Title and Desc.
* Added cards icons
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
