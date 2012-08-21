<?php // iframe_header( __('Install WooThemes') ); ?>
<?php
	define( 'AUTOSAVE_INTERVAL', '' );
	
	$_admin_colour = '';
	
	$_admin_colour = $_GET['colours'];
	
	if ( $_admin_colour == '' ) { $_admin_colour = 'classic'; } // End IF Statement

$title = __( 'Install WooThemes', 'woothemes' );
$limit_styles = false;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title><?php bloginfo('name') ?> &rsaquo; <?php echo $title ?> &#8212; <?php _e('WordPress'); ?></title>
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
do_action('admin_print_styles');
do_action('admin_print_scripts');
do_action('admin_head');

$hook_suffix = '';
$admin_body_class = preg_replace('/[^a-z0-9_-]+/i', '-', $hook_suffix);
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
<div id="theme-information" class="theme-install-php">

<div class="available-theme">
<img src="<?php echo $theme_data->thumbnail; ?>" width="300" class="theme-preview-img" />
<h3><?php echo $theme_data->name_solo; ?></h3>
<p><?php echo $theme_data->package; ?></p>
<p><?php echo $theme_data->date_formatted; ?></p>

<br class="clear">
</div>

<p class="action-button">
	<a class="button" id="cancel" href="#" onclick="tb_close(); return false;"><?php _e( 'Cancel', 'woothemes' ); ?></a> <a class="button-primary" id="install" href="themes.php?page=woo-installer&amp;woo-action=install-theme&amp;theme-key=<?php echo $theme_key; ?>&amp;theme_id=<?php echo $theme_data->id; ?>" target="_parent"><?php _e( 'Install Now', 'woothemes' ); ?></a>
	<br class="clear">
</p>

</div><!--/#theme-information-->
<?php // iframe_footer(); exit; ?>
<?php //We're going to hide any footer output on iframe pages, but run the hooks anyway since they output Javascript or other needed content. ?>
	<div class="hidden">
<?php
	do_action('admin_footer', '');
	do_action('admin_print_footer_scripts');
?>
	</div>
<script type="text/javascript">if(typeof wpOnload=="function")wpOnload();</script>
</body>
</html>