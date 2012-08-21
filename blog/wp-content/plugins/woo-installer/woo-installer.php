<?php
/*
Plugin Name: WooInstaller
Plugin URI: http://woothemes.com/
Description: Easily download and install your purchased WooThemes, right from the WordPress admin.
Author: Matty at WooThemes
Author URI: http://woothemes.com/
Version: 1.0.2
Stable tag: 1.0.2
License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

require_once( 'classes/woo-installer.class.php' );

global $woo_installer;

$woo_installer = new Woo_Installer( dirname( __FILE__ ), trailingslashit( WP_PLUGIN_URL ) . plugin_basename( dirname( __FILE__ ) ), 'woo-installer_', plugin_basename( __FILE__ ) );
?>