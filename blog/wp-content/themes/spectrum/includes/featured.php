
<div id="loopedSlider">
    <?php $woo_featured_tags = get_option('woo_featured_tags'); if ( ($woo_featured_tags != '') && (isset($woo_featured_tags)) ) { ?>
    <?php
		$featposts = get_option('woo_featured_entries'); // Number of featured entries to be shown
		$GLOBALS['feat_tags_array'] = explode(',',get_option('woo_featured_tags')); // Tags to be shown
        foreach ($GLOBALS['feat_tags_array'] as $tags){ 
			$tag = get_term_by( 'name', trim($tags), 'post_tag', 'ARRAY_A' );
			if ( $tag['term_id'] > 0 )
				$tag_array[] = $tag['term_id'];
		}
    ?>
	
	<?php $saved = $wp_query; query_posts(array('tag__in' => $tag_array, 'showposts' => $featposts)); ?>
	<?php if (have_posts()) : $count = 0; ?>

    <div class="container">
    
        <div class="slides">
        
            <?php while (have_posts()) : the_post(); ?>
			<?php if (!woo_image('return=true')) continue; // Skip post if it doesn't have an image ?>    
            <?php $GLOBALS['shownposts'][$count] = $post->ID; $count++; ?>
            
            <div id="slide-<?php echo $count; ?>" class="slide">
        
            	<?php woo_get_image('image',620,250,'feat-image'); ?>
            	
            	<div class="slide-content">
            	
       		     	<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
       		     	
       		     	<p><?php echo woo_excerpt( get_the_excerpt(), '150'); ?></p>
       		     	       		 		
       		 	</div><!-- /.slide-content -->
       		     	
       		    <div class="fix"></div>
        
            </div>
            
		<?php endwhile; ?> 
			
		</div><!-- /.slides -->
		
		<?php if ($count > 1) : ?>
		<ul class="nav-buttons">
    		<li id="n"><a href="#" class="next"><img src="<?php bloginfo('template_directory'); ?>/images/btn-slider-next.png" alt="&gt;" /></a></li>
            <li id="p"><a href="#" class="previous"><img src="<?php bloginfo('template_directory'); ?>/images/btn-slider-prev.png" alt="&lt;" /></a></li>
        </ul>
        <?php endif; ?>
		
    </div><!-- /.container -->
    
	<div class="fix"></div>
    
    <?php endif; $wp_query = $saved; ?> 
    <?php if (get_option('woo_exclude') <> $GLOBALS['shownposts']) update_option("woo_exclude", $GLOBALS['shownposts']); ?>
    
     <?php } else { ?>
     	<p class="note"><?php _e('Please setup Featured Panel tag(s) in your options panel. You must setup tags that are used on active posts.','woothemes'); ?></p>
     <?php } ?>   
</div><!-- /#loopedSlider -->
