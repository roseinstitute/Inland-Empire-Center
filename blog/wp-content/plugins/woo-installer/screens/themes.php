<?php
	global $title, $current_user;
	
	get_currentuserinfo();
	
	$_admin_colour = '';
	
	$_admin_colour = $current_user->admin_color;
	
	if ( $_admin_colour == '' ) { $_admin_colour = 'classic'; } // End IF Statement
	
	// Reset the Thickbox popup to an appropriate size.
	// wp_deregister_script( 'media-upload' );
	
	$current_page = 1;
	$per_page = 9;
	
	if ( isset( $_GET['pagenum'] ) && is_numeric( $_GET['pagenum'] ) ) {
	
		$current_page = $_GET['pagenum'];
	
	} // End IF Statement
	
	$offset = ( ( $per_page * $current_page ) - $per_page ) + 1;
	
	if ( $offset == 0 ) { $offset = 1; } // End IF Statement
	
	$themes = $this->get_purchased_themes( $offset, $per_page );
	
	$themes_displayed = count( $themes );
	
	// Pagination variables.
	$total = $this->total_themes;
	
	if ( $total < $per_page ) { $total = $per_page; } // End IF Statement
	
	$_admin_colour = '';
	
	$_admin_colour = $current_user->admin_color;
	
	if ( $_admin_colour == '' ) { $_admin_colour = 'classic'; } // End IF Statement
?>
<div class="wrap">

<?php screen_icon(); ?>
<h2><?php echo $title; ?></h2>

<h3 class="alignleft"><?php _e( 'Available Themes', 'woothemes' ); ?></h3>

<div id="woo-account" class="alignright">
	<span class="current-woo-user"><?php printf( __( 'WooThemes account in use: %s', 'woothemes' ), '<strong class="username">' . $this->user . '</strong>' ); ?></span><!--/.current-woo-user-->
	<small>( <a href="<?php echo admin_url( 'themes.php?page=woo-installer&amp;woo-action=switch-account' ); ?>" class="change-account"><?php echo __( 'Switch Account', 'woothemes' ); ?></a> )</small>
</div><!--/#woo-account-->

<div class="clear"></div><!--/.clear-->

<?php echo $this->pagination_links( $total, $current_page, $per_page ); ?>

<table id="availablethemes" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
<?php

	if ( $themes ) {
		
		$per_row = 3;
		$counter = 1;
		
		$current_column = 1;
		
		foreach ( $themes as $key => $theme ) {
		
		$_class = $theme->css_class . ' available-theme';
		
		if ( $counter <= $per_row ) {
		
			$_class .= ' top';
		
		} // End IF Statement
		
		if ( $current_column == 1 ) {
		
			$_class .= ' left';
		
		} // End IF Statement
		
		if ( $current_column == $per_row ) {
		
			$_class .= ' right';
		
		} // End IF Statement
		
		$_is_installed = false;
		
		// If the theme is already installed, don't display the "Install" link.
		if ( is_dir( WP_CONTENT_DIR . '/themes/' . $theme->css_class ) ) {
		
			$_is_installed = true;
			
		} // End IF Statement
?>
		<td id="<?php echo $theme->id; ?>" class="<?php echo $_class; ?>">
			<?php // if ( $this->remote_file_exists( $theme->thumbnail ) ) : ?>
			<a href="<?php echo $this->install_url; ?>&amp;theme_key=<?php echo $key; ?>&amp;theme_id=<?php echo $theme->id; ?>&amp;colours=<?php echo $_admin_colour; ?>&amp;TB_iframe=true&amp;tbWidth=500&amp;tbHeight=385" class="thickbox thickbox-preview screenshot">
				<img src="<?php echo $theme->thumbnail; ?>" alt="<?php echo $theme->name; ?>" width="240" />
			</a>
			<?php // endif; ?>
			<h3><?php echo $theme->name; ?></h3>
			<?php if ( $theme->launch_date == '0000-00-00' ) {} else { ?><p class="description"><abbr title="<?php echo $theme->timestamp; ?>"><?php echo $theme->date_formatted; ?></abbr></p><?php } // End IF Statement ?>
			<span class="action-links">
				<?php
					// If the theme is already installed, don't display the "Install" link.
					if ( $_is_installed ) {
			
						$theme_data = get_theme_data( trailingslashit( WP_CONTENT_DIR ) . 'themes/' . $theme->css_class . '/style.css' );
					
						$current_version = $theme_data['Version'];
				?>
				<?php echo __( 'Current Version', 'woothemes' ) . ': <strong>' . $current_version . '</strong>'; ?>
					<small>( <a href="<?php echo $this->upgrade_url; ?>&amp;theme_key=<?php echo $key; ?>&amp;theme_id=<?php echo $theme->id; ?>&amp;current-version=<?php echo $current_version; ?>&amp;colours=<?php echo $_admin_colour; ?>&amp;TB_iframe=true&amp;tbWidth=500&amp;tbHeight=385" class="activatelink thickbox thickbox-preview onclick" title="<?php printf( __( 'Re-install &quot;%s&quot;' ), $theme->name ); ?>"><?php _e( 'Re-install', 'woothemes' ); ?></a> )</small>
				
				<?php } else { ?>
				
				<a href="<?php echo $this->install_url; ?>&amp;theme_key=<?php echo $key; ?>&amp;theme_id=<?php echo $theme->id; ?>&amp;colours=<?php echo $_admin_colour; ?>&amp;TB_iframe=true&amp;tbWidth=500&amp;tbHeight=385" class="activatelink thickbox thickbox-preview onclick" title="Install “<?php echo $theme->name; ?>”"><?php _e( 'Install', 'woothemes' ); ?></a>
				
				<?php } // End IF Statement ?>
			</span><!--/.action-links-->
		</td>
<?php		
		$current_column++;
		
		if ( $current_column > $per_row ) { $current_column = 1; } // End IF Statement
		
			if ( ( $counter % $per_row == 0 ) ) {
			
				echo '</tr><tr>';
				
			} // End IF Statement
			
			$counter++;
		
		} // End FOREACH Loop
		
		// Fill in any remaining columns to even out the grid.
		
		$outstanding_columns = $per_row - ( $current_column - 1 );
		
		if ( $outstanding_columns ) {
		
			for ( $i = 0; $i < $outstanding_columns; $i++ ) {
			
				$_class .= ' bottom';
				
				if ( $i == ( $outstanding_columns - 1 ) ) {
				
					$_class .= ' right';
				
				} // End IF Statement
			
				echo '<td class="' . $_class . '">&nbsp;</td>' . "\n";
			
			} // End FOR Loop
		
		} // End IF Statement
	
	} // End IF Statement
	
?>
		</tr>
	</tbody>
</table><!--/#availablethemes-->

<?php echo $this->pagination_links( $total, $current_page, $per_page ); ?>

</div><!--/.wrap-->