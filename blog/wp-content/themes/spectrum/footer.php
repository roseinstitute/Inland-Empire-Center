
	<?php if ( woo_active_sidebar('footer-1') ||
			   woo_active_sidebar('footer-2') || 
			   woo_active_sidebar('footer-3') || 
			   woo_active_sidebar('footer-4') ) : ?>
	<div id="footer-widgets">
	
		<div class="col-full">

			<div class="block">
        		<?php woo_sidebar('footer-1'); ?>    
			</div>
			<div class="block">
        		<?php woo_sidebar('footer-2'); ?>    
			</div>
			<div class="block">
        		<?php woo_sidebar('footer-3'); ?>    
			</div>
			<div class="block last">
        		<?php woo_sidebar('footer-4'); ?>    
			</div>
			
			<div class="fix"></div>
		
		</div><!-- /.col-full -->

	</div><!-- /#footer-widgets  -->
    <?php endif; ?>
    
	<div id="footer">
	
		<div class="inner">
	
			<div id="credits" class="col-left">
            <?php if(get_option('woo_footer_left') == 'true'){
                echo stripslashes(get_option('woo_footer_left_text'));
            } else { ?>
				<?php if (get_option('woo_footer_logo_enabled') == "true") : ?>
				
					<a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('description'); ?>">
						<?php if(get_option('woo_footer_logo')) { ?>
							<img class="footer-logo" src="<?php echo get_option('woo_footer_logo'); ?>" alt="Footer logo" />
						<?php } else { ?>
							<img class="footer-logo" src="<?php bloginfo('template_directory'); ?>/images/logo-footer.png" alt="Footer logo" />
						<?php } ?>
					</a>
				
				<?php endif; ?>	
				
			    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo(); ?>. <?php _e('All Rights Reserved.', 'woothemes') ?><br />
			    <?php _e('Powered by', 'woothemes') ?> <a href="http://www.wordpress.org">WordPress</a>. <?php _e('Designed by', 'woothemes') ?> <a href="<?php $aff = get_option('woo_footer_aff_link'); if(!empty($aff)) { echo $aff; } else { echo 'http://www.woothemes.com'; } ?>"><img src="<?php bloginfo('template_directory'); ?>/images/woothemes.png" width="74" height="19" alt="Woo Themes" /></a></p>
            <?php } ?>
			</div>
			
			<div id="footer-search" class="col-right">
				
            <?php if(get_option('woo_footer_right') == 'true'){
                echo stripslashes(get_option('woo_footer_right_text'));
            } else { ?>
				<?php include ( TEMPLATEPATH . '/search-form.php' ); ?>
            <?php } ?>
				
			</div><!-- /#footer-search -->
			
		</div><!-- /.inner -->
		
		<div class="fix"></div>
		
	</div><!-- /#footer  -->

</div><!-- /#background -->

</div><!-- /#wrapper -->
<?php wp_footer(); ?>
<?php woo_foot(); ?>
</body>
</html>