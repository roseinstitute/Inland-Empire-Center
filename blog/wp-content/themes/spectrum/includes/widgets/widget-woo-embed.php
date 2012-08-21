<?php
/*---------------------------------------------------------------------------------*/
/* Embed Widget */
/*---------------------------------------------------------------------------------*/


class Woo_EmbedWidget extends WP_Widget {

	/*----------------------------------------
	  Constructor.
	  ----------------------------------------
	  
	  * The constructor. Sets up the widget.
	----------------------------------------*/
	
	function Woo_EmbedWidget () {
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_woo_embedwidget', 'description' => __( 'Display the embed code from posts in tab-like fashion.', 'woothemes' ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'woo_embedwidget' );

		/* Create the widget. */
		$this->WP_Widget( 'woo_embedwidget', __('Woo - Embed/Video', 'woothemes' ), $widget_ops, $control_ops );
		
	} // End Constructor

	function widget($args, $instance) { 
		extract( $args ); 
		$title = $instance['title'];
		$limit = $instance['limit'];
		
		$cat_id = $instance['cat_id'];
		$tag = $instance['tag'];
		
		$width = $instance['width'];
		$height = $instance['height'];
		
		$query_args = array( 'numberposts' => $limit, 'meta_key' => 'embed' );
			
		if( ! empty( $tag ) ) {
			$query_args['tag'] = $tag;
		} else {
			$query_args['cat'] = $cat_id;
		}

		$myposts = get_posts( $query_args );

		$post_list = '';
		$count = 0;
		$active = "active";
		$display = "";
	
        echo $before_widget; ?>
       
        <?php

			if ( $title != '' ) { echo $before_title . $title . $after_title; } ?>

            <?php    
		
			if( is_array( $myposts ) && ( count( $myposts ) > 0 ) ) {
			
				foreach($myposts as $mypost) {
					$embed = woo_embed( 'key=embed&width=' . $width . '&height=' . $height . '&class=widget_video&id=' . $mypost->ID );

					if($embed) {
						$count++;
						if($count > 1) {$active = ''; $display = "style='display:none'"; }
						?>
						<div class="widget-video-unit" <?php echo $display; ?> >
						<?php
							$title = get_the_title( $mypost->ID );
						
							echo '<h4>' . $title  . "</h4>\n";
							
							echo $embed;
							
							$post_list .= '<li class="' . $active . '"><a href="#">' . $title . '</a></li>' . "\n";
						?>
						</div>
						<?php
					}
				}
			}
		?>
        <ul class="widget-video-list">
        	<?php echo $post_list; ?>
        </ul>

        <?php
			
		echo $after_widget;

	}

	function update($new_instance, $old_instance) {                
		return $new_instance;
	}

	function form($instance) {        
		$title = esc_attr($instance['title']);
		$limit = esc_attr($instance['limit']);
		$cat_id = esc_attr($instance['cat_id']);
		$tag = esc_attr($instance['tag']);

		$width = esc_attr($instance['width']);
		$height = esc_attr($instance['height']);
		
		if( empty( $limit ) ) { $limit = 10; }
		if( empty( $width ) ) { $width = 300; }
		if( empty( $height ) ) { $height = 220; }

		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','woothemes' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
        </p>
       <p>
	   	   <label for="<?php echo $this->get_field_id( 'cat_id' ); ?>"><?php _e( 'Category:','woothemes' ); ?></label>
	       <?php $cats = get_categories(); ?>
	       <select name="<?php echo $this->get_field_name( 'cat_id' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'cat_id' ); ?>">
           <option value="">Disabled</option>
			<?php
           	foreach ($cats as $cat){
           	?><option value="<?php echo $cat->cat_ID; ?>" <?php if($cat_id == $cat->cat_ID){ echo "selected='selected'";} ?>><?php echo $cat->cat_name . ' (' . $cat->category_count . ')'; ?></option><?php
           	}
           ?>
           </select>
       </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tag' ); ?>">Or <?php _e( 'Tag:','woothemes' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'tag' ); ?>" value="<?php echo $tag; ?>" class="widefat" id="<?php echo $this->get_field_id( 'tag' ); ?>" />
        </p>

         <p>
            <label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Size:','woothemes' ); ?></label>
            <input type="text" size="2" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $width; ?>" class="" id="<?php echo $this->get_field_id( 'width' ); ?>" /> W
            <input type="text" size="2" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $height; ?>" class="" id="<?php echo $this->get_field_id( 'height' ); ?>" /> H

        </p>
        
         <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e( 'Limit (optional):', 'woothemes' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo $limit; ?>" class="" id="<?php echo $this->get_field_id( 'limit' ); ?>" />
        </p>

        <?php
	}
} 

register_widget('Woo_EmbedWidget');

if(is_active_widget( null,null,'woo_embedwidget' ) == true) {
	add_action('wp_footer','woo_widget_embed_head');
}

function woo_widget_embed_head(){
?>
<!-- Woo Video Player Widget -->
<script type="text/javascript">
	jQuery(document).ready(function(){
		var list = jQuery('ul.widget-video-list');
		list.find('a').click(function(){
			var clickedTitle = jQuery(this).text();
			jQuery(this).parent().parent().find('li').removeClass('active');
			jQuery(this).parent().addClass('active');
			var videoHolders = jQuery(this).parent().parent().parent().children('.widget-video-unit');
			videoHolders.each(function(){
				if(clickedTitle == jQuery(this).children('h4').text()){
					videoHolders.hide();
					jQuery(this).show();
				}
			})
			return false;
		})
	})
</script>
<?php
}
?>