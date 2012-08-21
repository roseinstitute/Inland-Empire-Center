<?php
	class Woo_Installer_Upgrade_Skin extends Theme_Installer_Skin {
	
		var $installed_theme_key;
	
		function header() {
			if ( $this->done_header )
				return;
			$this->done_header = true;
			echo '<div class="wrap">';
			echo screen_icon();
			echo '<h2>' . __( 'Installing your WooTheme&#8230;', 'woothemes' ) . '</h2>';
		}
		
		function after() {
	
			$update_actions = array();
			
			if (
				!empty($this->upgrader->result['destination_name']) &&
				($theme_info = $this->upgrader->theme_info()) &&
				!empty($theme_info)
				) {
	
				$name = $theme_info['Name'];
				$stylesheet = $this->upgrader->result['destination_name'];
				$template = !empty($theme_info['Template']) ? $theme_info['Template'] : $stylesheet;
				
				$template = $this->installed_theme_key;
				$stylesheet = $this->installed_theme_key;
				
				$theme_info = get_theme_data( trailingslashit( WP_CONTENT_DIR ) . 'themes/' . $stylesheet );
				
				if ( !empty($theme_info['Template']) ) {
				
					$template = $theme_info['Template'];
				
				} // End IF Statement
				
				$preview_link = htmlspecialchars( add_query_arg( array('preview' => 1, 'template' => $template, 'stylesheet' => $stylesheet, 'TB_iframe' => 'true' ), trailingslashit(esc_url(get_option('home'))) ) );
				$activate_link = wp_nonce_url("themes.php?action=activate&amp;template=" . urlencode($template) . "&amp;stylesheet=" . urlencode($stylesheet), 'switch-theme_' . $template);
				
				$update_actions['preview'] = '<a href="' . $preview_link . '" class="thickbox thickbox-preview" title="' . esc_attr(sprintf(__( 'Preview &#8220;%s&#8221;', 'woothemes' ), $name)) . '">' . __( 'Preview', 'woothemes' ) . '</a>';
				$update_actions['activate'] = '<a href="' . $activate_link .  '" class="activatelink" title="' . esc_attr( sprintf( __( 'Activate &#8220;%s&#8221;', 'woothemes' ), $name ) ) . '">' . __( 'Activate', 'woothemes' ) . '</a>';
				
				if ( ( ! $this->result || is_wp_error($this->result) ) || $stylesheet == get_stylesheet() )
					unset($update_actions['preview'], $update_actions['activate']);
					
				$update_actions['themes_page'] = '<a href="' . admin_url('themes.php?page=woo-installer') . '" title="' . esc_attr( __( 'Install other WooThemes', 'woothemes' ) ) . '" target="_parent">' . __( 'Install other WooThemes', 'woothemes' ) . '</a>';
			}
	
			$update_actions = apply_filters('update_theme_complete_actions', $update_actions, $this->installed_theme_key);
			if ( ! empty($update_actions) )
				$this->feedback('<strong>' . __('Actions:') . '</strong> ' . implode(' | ', (array)$update_actions));
		}
	
	} // End Class
?>