<?php
	define( 'AUTOSAVE_INTERVAL', '' );
	
	$_admin_colour = '';
	
	$_admin_colour = $_GET['colours'];
	
	if ( $_admin_colour == '' ) { $_admin_colour = 'classic'; } // End IF Statement

$title = __( 'Upgrade WooTheme', 'woothemes' );
$limit_styles = false;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php echo get_option( 'blog_charset' ); ?>" />
<title><?php bloginfo( 'name' ); ?> &rsaquo; <?php echo $title; ?> &#8212; <?php _e( 'WordPress', 'woothemes' ); ?></title>
<?php
wp_enqueue_style( 'global' );
// if ( ! $limit_styles )
	wp_enqueue_style( 'wp-admin' );

// wp_enqueue_style( 'colors' );

wp_enqueue_style( 'theme-install' );
wp_enqueue_script( 'theme-install' );

add_thickbox();	
wp_enqueue_script( 'theme-preview' );

echo '<link rel="stylesheet" href="' . get_bloginfo('url') . '/wp-admin/css/colors-' . $_admin_colour . '.css" media="all" type="text/css" />' . "\n";
?>
<script type="text/javascript">
//<![CDATA[
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
function tb_close(){var win=window.dialogArguments||opener||parent||top;win.tb_remove();}
//]]>
</script>
<?php
do_action( 'admin_print_styles' );
do_action( 'admin_print_scripts' );
do_action( 'admin_head' );

$hook_suffix = '';
$admin_body_class = preg_replace( '/[^a-z0-9_-]+/i', '-', $hook_suffix );
?>
</head>
<body<?php if ( isset($GLOBALS['body_id']) ) echo ' id="' . $GLOBALS['body_id'] . '"'; ?>  class="no-js <?php echo $admin_body_class; ?>">
<script type="text/javascript">
//<![CDATA[
(function(){
var c = document.body.className;
c = c.replace(/no-js/, 'js');
document.body.className = c;
})();
//]]>
</script>

<?php
	$theme_key = $_GET['theme_key'];
	$theme_id = $_GET['theme_id'];
	$current_version = $_GET['current-version'];
	
	$is_upgrade = $this->check_for_upgrade( $theme_key, $theme_id, $current_version );
	
	if ( $is_upgrade ) { $latest_version = $is_upgrade; } else { $latest_version = $current_version; } // End IF Statement
	
	if ( $theme_key == '' || is_numeric( $theme_key ) || $theme_id == '' || ! is_numeric( $theme_id ) ) {
?>
	<script type="text/javascript">
	<!--
		tb_close(); return false;
	-->
	</script>
<?php
	} // End IF Statement
	
	$theme_data = $this->get_single_theme_data( $theme_id );
?>
<div id="theme-information" class="theme-upgrade-php">
<div class="available-theme">
<h3><?php echo $theme_data->name_solo; ?> <small>- <?php _e( 'Theme Upgrade', 'woothemes' ); ?></small></h3>
<p><?php _e( 'While using your WooTheme, you may have customised files and made it your own. We don\'t want to lose that.', 'woothemes' ); ?></p>
<p><?php _e( 'Therefore, when upgrading your WooTheme, a backup copy of the current version is made, preserving your customisations.', 'woothemes' ); ?> 
<?php _e( 'A fresh copy of the latest version of your theme is then downloaded and installed for you.', 'woothemes' ); ?></p>
<p><?php _e( 'If you\'ve chosen the "overwrite" option, your current version of your WooTheme will be overwritten with the latest version.', 'woothemes' ); ?></p>

<br class="clear">
</div>
<?php
	if ( $current_version == $latest_version ) {
?>
<div class="updated fade"><p><strong><?php echo sprintf( __( 'Your version of %s (%s) is the latest version.', 'woothemes' ), $theme_data->name_solo, $current_version ); ?></p></div>
<?php
	} else {
?>
<div class="updated fade"><p><strong><?php _e( 'Version Available', 'woothemes' ); ?>:</strong> <?php echo $latest_version; ?></p></div>
<?php
	} // End IF Statement
?>
<p class="action-button">
	
	<!-- Cancel Button. -->
	
	<a class="button" id="cancel" href="#" onclick="tb_close(); return false;"><?php _e( 'Cancel', 'woothemes' ); ?></a> 
	
	<!-- Upgrade to the latest version, overwriting the existing theme files. -->
	
	<span class="alignright">
	
		<a class="button alignleft" style="margin-top: 10px;" id="upgrade" href="themes.php?page=woo-installer&amp;woo-action=upgrade-theme-overwrite&amp;theme-key=<?php echo $theme_key; ?>&amp;theme_id=<?php echo $theme_data->id; ?>" target="_parent"><?php _e( 'Overwrite To Latest Version', 'woothemes' ); ?></a>
		
		<!-- Download a copy of the latest version of the theme into a new theme folder. -->
		
		<a class="button-primary" id="install" href="themes.php?page=woo-installer&amp;woo-action=upgrade-theme&amp;theme-key=<?php echo $theme_key; ?>&amp;theme_id=<?php echo $theme_data->id; ?>" target="_parent"><?php _e( 'Install Latest Version', 'woothemes' ); ?></a>
		
		<br class="clear">
	
	</span>
	
	<br class="clear">
</p>
</div><!--/#theme-information-->
<?php //We're going to hide any footer output on iframe pages, but run the hooks anyway since they output Javascript or other needed content. ?>
	<div class="hidden">
<?php
	do_action( 'admin_footer', '' );
	do_action( 'admin_print_footer_scripts' );
?>
	</div>
<script type="text/javascript">if(typeof wpOnload=="function")wpOnload();</script>
</body>
</html>