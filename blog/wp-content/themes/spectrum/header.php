<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

<title><?php woo_title(); ?></title>
<?php woo_meta(); ?>

<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php $feedurl = get_option('woo_feed_url'); if ( $feedurl ) { echo $feedurl; } else { echo get_bloginfo_rss('rss2_url'); } ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
      
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' );  ?>
<?php wp_head(); ?>
<?php woo_head(); ?>

<?php if ( !$paged && get_option('woo_featured') == "true" ) { ?>
<script type="text/javascript">
jQuery(window).load(function(){
	jQuery("#loopedSlider").loopedSlider({
	<?php
		$autoStart = 0;
		$slidespeed = 600;
		if ( get_option("woo_slider_auto") == "true" ) 
		   $autoStart = get_option("woo_slider_interval") * 1000;
		else 
		   $autoStart = 0;
		if ( get_option("woo_slider_speed") <> "" ) 
			$slidespeed = get_option("woo_slider_speed") * 1000;
	?>
		autoStart: <?php echo $autoStart; ?>, 
		slidespeed: <?php echo $slidespeed; ?>, 
		autoHeight: true
	});
});
</script>
<?php } ?>

</head>

<body <?php body_class(); ?>>

<?php woo_top(); ?>

<div id="wrapper">
<div id="background">
<!--
	<div id="top-nav" class="col-full">
		<?php
		if ( function_exists('has_nav_menu') && has_nav_menu('primary-menu') ) {
			wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_class' => 'nav', 'theme_location' => 'primary-menu' ) );
		} else {
		?>
        <ul class="nav">
			<?php 
        	if ( get_option('woo_custom_nav_menu') == 'true' ) {
        		if ( function_exists('woo_custom_navigation_output') )
					woo_custom_navigation_output('name=Woo Menu 1&depth=6');

			} else { ?>
            	
	            <?php if ( is_home() OR is_front_page()) $highlight = "page_item current_page_item"; else $highlight = "page_item"; ?>
	            <li class="<?php echo $highlight; ?>"><a href="<?php bloginfo('url'); ?>"><?php _e('Home', 'woothemes') ?></a></li>
	            <?php wp_list_pages('sort_column=menu_order&depth=6&title_li=&exclude='.get_option('woo_pages_exclude')); ?>
	            
           <?php } ?>
        </ul><!-- /#nav -->
        <?php } ?>       
       <span class="nav-item-right"><a href="<?php $feedurl = get_option('woo_feed_url'); if ( $feedurl ) { echo $feedurl; } else { echo get_bloginfo_rss('rss2_url'); } ?>"><img src="<?php bloginfo('template_url'); ?>/images/ico-rss.png" alt="RSS Feed" /></a></span>   
	</div><!-- /#top-nav -->
           
	<div id="header" class="col-full">
 		       
		<div id="logo">
	       
		<?php if (get_option('woo_texttitle') <> "true") : $logo = get_option('woo_logo'); ?>
            <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('description'); ?>">
                <img src="<?php if ($logo) echo $logo; else { bloginfo('template_directory'); ?>/images/logo.png<?php } ?>" alt="<?php bloginfo('name'); ?>" />
            </a>
        <?php endif; ?> 
        
        <?php if( is_singular() ) : ?>
            <span class="site-title"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></span>
        <?php else : ?>
            <h1 class="site-title"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
        <?php endif; ?>
            <span class="site-description"><?php bloginfo('description'); ?></span>
	      	
		</div><!-- /#logo -->
	       
		<?php if (get_option('woo_ad_top') == 'true') { ?>
        <div id="topad">
	    	<?php include (TEMPLATEPATH . "/ads/top_ad.php"); ?>
	   	</div><!-- /#topad -->
        <?php } ?>
       
	</div><!-- /#header -->
    
	<div id="main-nav" class="col-full">
		<?php
		if ( function_exists('has_nav_menu') && has_nav_menu('secondary-menu') ) {
			wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_class' => 'nav', 'theme_location' => 'secondary-menu' ) );
		} else {
		?>
        <ul class="nav">
			<?php 
        	if ( get_option('woo_custom_nav_menu') == 'true' ) {
        		if ( function_exists('woo_custom_navigation_output') )
					woo_custom_navigation_output('name=Woo Menu 2&depth=6');

			} else { ?>
            	
	            <?php if ( is_home() OR is_front_page()) $highlight = "page_item current_page_item"; else $highlight = "page_item"; ?>
	            <li class="<?php echo $highlight; ?>"><a href="<?php bloginfo('url'); ?>"><?php _e('Home', 'woothemes') ?></a></li>
	            <?php wp_list_categories('sort_column=menu_order&depth=6&title_li=&exclude='.get_option('woo_cats_exclude')); ?>
	            
	        <?php } ?>
	            
        </ul><!-- /#nav -->
        <?php } ?>       
	</div><!-- /#main-nav -->
       