=== WooInstaller ===
Contributors: mattyza
Donate link: http://www.woothemes.com/
Tags: woothemes, theme installer, premium themes
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 1.0.1

Easily install your WooThemes WordPress themes from the WordPress administration area.

== Description ==

WooInstaller shows all WordPress theme purchased through your WooThemes account in an easy-to-view grid. It provides an easy-to-use installer for loading your theme directly into your WordPress installation.

== Installation ==

Installing WooInstaller is really easily. Simply follow the steps below:

1. Upload the `woo-installer` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Click the "Install WooThemes" link that appears under the "Appearance" menu.
1. Load in your WooThemes account details (you should only have to do this once).

== Frequently Asked Questions ==

= I receive an error message, mentioning that cURL is required? =

Yes. The cURL library is required in order for WooInstaller to function. Please contact your web hosting provider and request that they enable cURL on your web server.

== Screenshots ==

1. The WooInstaller login screen.
2. Your purchased WooThemes in a neat grid.
3. Your WooTheme has been installed successfully.

== Changelog ==

= 1.0.1 =
* /classes/woo-installer.class.php - Updated references to the admin screen to use a dynamic reference. Fixed temporary directory creation issue on Windows servers.
* /screens/login.php - Updated form action to use a dynamic reference to the admin screen.

= 1.0.0 =
* First release!

== Upgrade Notice ==

= 1.0.0 =
* First release!