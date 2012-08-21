<?php
	class Woo_Installer_Upgrader extends Theme_Upgrader {
	
		var $current_wootheme_name;
	
		function upgrade_strings() {
			$this->strings['up_to_date'] = __( 'The theme is at the latest version.', 'woothemes' );
			$this->strings['no_package'] = sprintf( __( '%s upgrade package not available.', 'woothemes' ), $this->current_wootheme_name );
			$this->strings['downloading_package'] = sprintf( __( 'Downloading %s update package from <span class="code">WooThemes.com</span> (please be patient)&#8230;', 'woothemes' ), $this->current_wootheme_name );
			$this->strings['unpack_package'] = __( 'Unpacking the update&#8230;', 'woothemes' );
			$this->strings['remove_old'] = __( 'Removing the old version of the theme&#8230;', 'woothemes' );
			$this->strings['remove_old_failed'] = __( 'Could not remove the old theme.', 'woothemes' );
			$this->strings['process_failed'] = sprintf( __( '%s WooTheme upgrade failed.', 'woothemes' ), $this->current_wootheme_name );
			$this->strings['process_success'] = sprintf( __( '%s WooTheme upgraded successfully.', 'woothemes' ), $this->current_wootheme_name );
		}
	
		function install_strings() {
			$this->strings['no_package'] = sprintf( __( '%s install package not available.', 'woothemes' ), $this->current_wootheme_name );
			$this->strings['downloading_package'] = sprintf( __( 'Downloading %s install package from <span class="code">WooThemes.com</span> (please be patient)&#8230;', 'woothemes' ), $this->current_wootheme_name );
			$this->strings['unpack_package'] = __( 'Unpacking the package&#8230;', 'woothemes' );
			$this->strings['installing_package'] = sprintf( __( 'Installing %s&#8230;', 'woothemes' ), $this->current_wootheme_name );
			$this->strings['process_failed'] = sprintf( __( '%s WooTheme install failed.', 'woothemes' ), $this->current_wootheme_name );
			$this->strings['process_success'] = sprintf( __( '%s WooTheme installed successfully.', 'woothemes' ), $this->current_wootheme_name );
		}
		
		function check_theme_version( $package, $theme_folder, $current_version ) {
	
			$this->init();
			$this->upgrade_strings();
	
			$is_newer_version = false;
	
			$options = array(
							'package' => $package,
							'destination' => WP_CONTENT_DIR . '/themes',
							'clear_destination' => false, //Do not overwrite files.
							'clear_working' => true, 
							'is_multi' => true
							);
	
			$version = $this->get_files_from_package( $options, $theme_folder );
		
			if ( $version > $current_version ) {
				
				$is_newer_version = true;
				
			} // End IF Statement
		
			return $is_newer_version;
			
		} // End check_theme_version()
		
		function get_files_from_package( $options, $theme_folder ) {
	
			$defaults = array( 	'package' => '', //Please always pass this.
								'destination' => '', //And this
								'clear_destination' => false,
								'clear_working' => true,
								'is_multi' => false,
								'hook_extra' => array() //Pass any extra $hook_extra args here, this will be passed to any hooked filters.
							);
	
			$options = wp_parse_args($options, $defaults);
			extract($options);
	
			//Connect to the Filesystem first.
			$res = $this->fs_connect( array(WP_CONTENT_DIR, $destination) );
			if ( ! $res ) //Mainly for non-connected filesystem.
				return false;
	
			if ( is_wp_error($res) ) {
				$this->skin->error($res);
				return $res;
			}
	
			if ( !$is_multi ) // call $this->header separately if running multiple times
				$this->skin->header();
	
			$this->skin->before();
	
			//Download the package (Note, This just returns the filename of the file if the package is a local file)
			$download = $this->download_package( $package );
			if ( is_wp_error($download) ) {
				$this->skin->error($download);
				$this->skin->after();
				return $download;
			}
	
			//Unzip's the file into a temporary directory
			$working_dir = $this->unpack_package( $download );
			if ( is_wp_error($working_dir) ) {
				$this->skin->error($working_dir);
				$this->skin->after();
				return $working_dir;
			}
			
			if ( $working_dir ) {
			
				$theme_data = get_theme_data( trailingslashit( $working_dir ) . $theme_folder . '/style.css' );
				
				$result = $theme_data['Version'];
			
			} // End IF Statement
			
			return $result;
		} // End get_files_from_package()
	
	} // End Class
?>