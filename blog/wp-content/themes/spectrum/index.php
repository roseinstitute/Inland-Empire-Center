<?php get_header(); ?>

    <div id="content" class="col-full">
		<div id="main" class="col-left">
		
		<?php $showfeatured = get_option('woo_featured'); if ($showfeatured <> "true") { if (get_option('woo_exclude')) update_option("woo_exclude", ""); } ?>
		<?php if ( !$paged && $showfeatured == "true" ) include ( TEMPLATEPATH . '/includes/featured.php' ); ?> 
                    
			<?php   
			// Exclude stored duplicates 
			$exclude = get_option('woo_exclude'); 
			// Exclude categories
			//$cat_exclude = array();
			$cats = explode(',',get_option('woo_home_exclude')); 
			foreach ($cats as $cat)
			  $cat_exclude[] = $cat;			
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
			$args = array(	'post__not_in' => $exclude, 
							'category__not_in' => $cat_exclude,
							'paged'=> $paged ); 
			query_posts($args);			
			?>
			
			<?php $count = 0; ?>
				
            	<?php if (have_posts()) : ?>
            		
            		<div id="recent-posts">
            			
            			<?php if(get_option('woo_home_post_heading') == 'true'){ ?>
            			<h3><?php _e('Recent Posts','woothemes'); ?></h3>
            			<?php } ?>
            			
            			<?php 
            			$large_thumb_h = get_option('woo_large_thumb_h');
            			if(empty($large_thumb_h)) { $large_thumb_h = 185;}
            			$large_placeholder_src = get_option('woo_large_placeholder');
            			
            			while (have_posts() && ($count < get_option('woo_recent_entries'))) : the_post(); $count++; 
            			
            			if(!empty($large_placeholder_src)) { $large_placeholder = '<a href="'.get_permalink().'" title="'. get_the_title() .'">' . woo_image('meta='.get_the_title().'&width=300&height='.$large_thumb_h.'&return=true&src='.$large_placeholder_src) . '</a>'; }
            			else { $large_placeholder = '<a href="'.get_permalink().'" title="'. get_the_title() .'"><img src="' . get_bloginfo('template_url') . '/images/empty.jpg" alt="' . get_the_title() .'" /></a>';}
            			?>
            				<div class="post">
																
								<?php if ( woo_image('return=true') ) { 
									woo_image('key=image&width=300&height=' . $large_thumb_h);

								}else{ 
								  echo $large_placeholder;						
								 }	?>

								
								<div class="heading">
            				    
            				    	<p class="meta">
            				    	    <span><?php the_category(', ') ?></span> - 
            				    	    <span><?php the_time(get_option('date_format')); ?></span> - 
    		    	    <span><?php comments_popup_link(__('0 Comments', 'woothemes'), __('1 Comment', 'woothemes'), __('% Comments', 'woothemes')); ?></span>
            				    	</p>
            				    
            				    	<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
            				    
            				    </div><!-- /.heading -->
            				     
            				</div><!-- /.post -->
            			                                    
       					<?php endwhile; ?>
       					
       					<div class="fix"></div>       				
       				</div><!-- /#recent-posts -->
       				
       				<?php if (get_option('woo_ad_content') == 'true') {  include (TEMPLATEPATH . "/ads/content_ad.php"); } ?>
       				
       				<!-- OLDER POSTS LOOP -->
   					<?php 
   					if (!is_paged() && get_option('woo_recent_entries') < get_option('posts_per_page')) :
   					$counter = 0;
   					$small_thumb_h = get_option('woo_small_thumb_h');
   					$small_thumb_w = get_option('woo_small_thumb_w');
   					if(empty($small_thumb_h) OR empty($small_thumb_w)) { $small_thumb_h = 60; $small_thumb_w = 60;}
   					$small_placeholder_src = get_option('woo_small_placeholder');
        			
        			$count2 = 0;
        			$ppp = get_option('posts_per_page') - $count;
        			
					$args = array(	'post__not_in' => $exclude, 
									'posts_per_page' => $ppp,
									'offset' => $count,
									'category__not_in' => $cat_exclude ); 

        			$my_query = new WP_Query($args);
        			
   					if($my_query->have_posts()) : ?>
       					
       				<div id="older-posts">
       					
       					<h3><?php _e('Older Posts','woothemes'); ?></h3>
       					
       					<?php while ($my_query->have_posts() && ($count >= get_option('woo_recent_entries'))) : $my_query->the_post(); $count++; $counter++; $count2++; ?>
	       					
	       					<?php
	       					if(!empty($small_placeholder_src)) { $small_placeholder = '<a href="'.get_permalink().'" title="'. get_the_title() .'">'. woo_image('meta='.get_the_title().'&width='.$small_thumb_w.'&height='.$small_thumb_h.'&return=true&src='.$small_placeholder_src) . '</a>'; }
	           				else { $small_placeholder = '<a href="'.get_permalink().'" title="'. get_the_title() .'"><img width="'.$small_thumb_w.'" height="'.$small_thumb_h.'" src="' . get_bloginfo('template_url') . '/images/empty_small.jpg" alt="' . get_the_title() .'" /></a>';}
	       					?>
            				
            				<div class="older-item">
            				    
            				    <?php if ( woo_image('return=true') ) { 
									woo_image('key=image&width='. $small_thumb_w .'&height=' . $small_thumb_h);
								}else{
								 	echo $small_placeholder;						
								 }	?>
            				    
            				    <span class="info">
            				    	<span class="meta-old"><?php the_category(', ') ?> - <?php the_time(get_option('date_format')); ?></span>            				    
            				    	<span class="title-old"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></span>
            				    </span> 				   
            				     
            				</div><!-- /.older-item -->
            				
            				<?php if($count2 == 2) { ?>
            					<div class="fix"></div>
            				<?php $count2 = 0; } ?>
            					
            			                                    
       					<?php endwhile; ?>
       					       					
       					<div class="fix"></div>
       					
       				</div><!-- /#older-posts -->
       				
	       			<?php endif; endif; ?>
       				<!-- /OLDER POSTS LOOP -->
       				
       			<?php endif; ?>
    
			<?php woo_pagenav(); ?>
                
		</div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>