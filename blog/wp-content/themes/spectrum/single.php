<?php get_header(); ?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
		           
            <?php if (have_posts()) : $count = 0; ?>
            <?php while (have_posts()) : the_post(); $count++; ?>
            
				<div <?php post_class(); ?>>

                    <h1 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
                    
                    <p class="post-meta">
                    	<span class="post-category"><?php the_category(', ') ?></span> | 
                    	<span class="post-date"><?php the_time(get_option('date_format')); ?></span>
                    	<?php _e('by', 'woothemes') ?> <span class="post-author"><?php the_author_posts_link(); ?></span> | 
                    	<span class="comments"><?php comments_popup_link(__('0 Comments', 'woothemes'), __('1 Comment', 'woothemes'), __('% Comments', 'woothemes')); ?></span>
   	                    <?php edit_post_link( __('{ Edit }', 'woothemes'), '<span class="small">', '</span>' ); ?>
                    </p>
                    
                    <div class="entry">
                    	<?php 
                    	$video = woo_get_embed('embed',620,400);
                    	$image = woo_image('key=image&return=true');
                    	if(!empty($video)) { echo $video;}
                    	elseif ( get_option('woo_thumb_single') == "true" AND (!empty($image))) { woo_image('width='.get_option('woo_single_w').'&height='.get_option('woo_single_h').'&class=thumbnail '. get_option('woo_single_align')); }?>
                    	
                    	<?php the_content(); ?>
                    	
					</div>
										
					<?php the_tags('<p class="tags">Tags: ', ', ', '</p>'); ?>

					<?php woo_subscribe_connect(); ?>

                    <?php woo_postnav(); ?>
                    
                </div><!-- /.post -->
                
                <?php if (get_option('woo_ad_content') == 'true') {  include (TEMPLATEPATH . "/ads/content_ad.php"); } ?>
                
                <?php
                $comm = get_option('woo_comments'); if ( $comm == "post" || $comm == "both" ) : ?>
	                <?php comments_template('', true); ?>
                <?php endif; ?>
                                                    
			<?php endwhile; else: ?>
				<div class="post none">
				
					<h1 class="title"><?php _e('Nothing found', 'woothemes') ?></h1>
				
                	<div class="entry">
                		<p><?php _e('The page you trying to reach does not exist, or has been moved. Please use the menus or the search box to find what you are looking for.', 'woothemes') ?></p>
                	</div>
                	                	
                </div><!-- /.post -->             
           	<?php endif; ?>  
        
		</div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>