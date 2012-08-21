<?php get_header(); ?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
        
        <?php if (have_posts()) : $count = 0; ?>
        
            <span class="archive_header"><?php _e('Search results', 'woothemes') ?>: <?php printf(the_search_query());?></span>
            
            <div class="fix"></div>
            
            <div id="recent-posts">
        
        <?php

        $large_thumb_h = get_option('woo_large_thumb_h');
        if(empty($large_thumb_h)) { $large_thumb_h = 185;}
       	$large_placeholder = get_option('woo_large_placeholder');

        while (have_posts()) : the_post(); $count++; 
            			

        if(!empty($large_placeholder_src)) { $large_placeholder = '<a href="'.get_permalink().'" title="'. get_the_title() .'">' . woo_image('meta='.get_the_title().'&width=300&height='.$large_thumb_h.'&return=true&src='.$large_placeholder_src) . '</a>'; }
        else { $large_placeholder = '<a href="'.get_permalink().'" title="'. get_the_title() .'"><img src="' . get_bloginfo('template_url') . '/images/empty.jpg" alt="' . get_the_title() .'" /></a>';}
         ?>
                                                                    
            <!-- Post Starts -->
    			   			
    		<div class="post">
													
				<?php if ( woo_image('return=true') ) { 
						woo_image('key=image&width=300&height=' . $large_thumb_h );
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
        
        <?php else: ?>
            <div class="post none">
				
				<h1 class="title"><?php _e('Nothing found', 'woothemes') ?></h1>
			
               	<div class="entry">
               		<p><?php _e('The page you trying to reach does not exist, or has been moved. Please use the menus or the search box to find what you are looking for.', 'woothemes') ?></p>
               	</div>
                	                	
            </div><!-- /.post -->  
        <?php endif; ?>  
    	
			<?php woo_pagenav(); ?>
                
		</div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>