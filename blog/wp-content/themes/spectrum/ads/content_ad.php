<div class="advert-content">

	<?php if (get_option('woo_ad_content_adsense') <> "") { echo stripslashes(get_option('woo_ad_content_adsense')); ?>
	
	<?php } else { ?>
	
		<a href="<?php echo get_option('woo_ad_content_url'); ?>"><img src="<?php echo get_option('woo_ad_content_image'); ?>" alt="advert" /></a>
		
	<?php } ?>	

</div>