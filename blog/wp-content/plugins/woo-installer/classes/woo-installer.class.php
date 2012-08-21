<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: Main class for the Woo_Installer WordPress plugin.
Date Created: 2010-11-09.
Author: Matty.
Since: 0.1.0


TABLE OF CONTENTS

- var $plugin_path
- var $plugin_url
- var $plugin_prefix
- var $plugin_base

- var $api_url
- var $install_url
- var $upgrade_url
- var $demo_url

- var $is_valid
- var $is_installing
- var $is_account_switch
- var $is_upgrading

- var $admin_screen

- var $installed_theme_key

- var $theme_to_install

- var $user
- var $pass

- var $themes
- var $total_themes

- function Woo_Installer (constructor)
- function init ()
- function authenticate_user ()
- function load_user_data ()
- function get_purchased_themes ()
- function install_theme ()
- function backup_existing_theme ()
- function upgrade_theme ()
- function check_for_upgrade ()
- function register_nav_menu_link ()
- function load_admin_screen ()
- function load_installer ()
- function get_api_data ()
- function remote_file_exists ()
- function get_theme_by_key ()
- function get_single_theme_data ()
- function get_theme_package ()
- function enqueue_scripts ()
- function admin_notice ()
- function contextual_help ()
- function activation ()
- function update ()
- function pagination_links ()
- function is_curl_installed ()

-----------------------------------------------------------------------------------*/

	class Woo_Installer {
	
		/*----------------------------------------
	 	  Class Variables
	 	  ----------------------------------------
	 	  
	 	  * Setup of variable placeholders, to be
	 	  * populated when the constructor runs.
	 	----------------------------------------*/
	 	
	 	var $plugin_path;
	 	var $plugin_url;
	 	var $plugin_prefix;
	 	var $plugin_base;
	 	
	 	var $api_url;
	 	var $install_url;
	 	var $upgrade_url;
	 	var $demo_url;
	 	
		var $is_valid;
		var $is_installing;
		var $is_account_switch;
		var $is_upgrading;
		
		var $admin_screen;
		
		var $installed_theme_key;
		
		var $theme_to_install;
		
		var $user;
		var $pass;
		
		var $themes;
		var $total_themes;
		
		/*----------------------------------------
	 	  Woo_Installer()
	 	  ----------------------------------------
	 	  
	 	  * Constructor function.
	 	  * Sets up the class and registers
	 	  * variable action hooks.
	 	  
	 	  * Params:
	 	  * - String $plugin_path
	 	  * - String $plugin_url
	 	  * - String $plugin_prefix
	 	  * - String $plugin_base
	 	----------------------------------------*/
		
		function Woo_Installer ( $plugin_path, $plugin_url, $plugin_prefix, $plugin_base ) {
		
			session_start();
		
			$this->plugin_path = $plugin_path;
	 		$this->plugin_url = $plugin_url;
	 		$this->plugin_prefix = $plugin_prefix;
	 		$this->plugin_base = $plugin_base;
	 		
	 		$this->api_url = 'http://www.woothemes.com/api';
	 		$this->install_url = get_bloginfo('url') . '/wp-admin/themes.php?page=woo-installer&woo-action=install-confirmation';
	 		$this->upgrade_url = get_bloginfo('url') . '/wp-admin/themes.php?page=woo-installer&woo-action=upgrade-confirmation';
	 		$this->demo_url = 'http://demo.woothemes.com/wp-content/themes/';
	 		
	 		$this->is_valid = 0;
	 		
	 		$this->user = '';
	 		$this->pass = '';
	 		
	 		$this->admin_screen = '';
	 		
	 		$this->themes = array();
	 		
	 		$this->init();
		
		} // End Woo_Installer()
		
		/*----------------------------------------
	 	  init()
	 	  ----------------------------------------
	 	  
	 	  * This guy runs the show.
	 	  * Rocket boosters... engage!
	 	----------------------------------------*/
		
		function init () {
		
			// Load plugin translations.
			load_plugin_textdomain( 'woothemes', false, $this->plugin_path . '/languages' );
			
			// Register navigation menu link.
			add_action( 'admin_menu', array( &$this, 'register_nav_menu_link' ) );
			
			// If we're in the "install a theme" popup or a theme installation, load that screen.
			add_action( 'plugins_loaded', array( &$this, 'load_installer' ) );
			
			// Load the appropriate scripts if on the install page.
			add_action( 'admin_print_scripts', array( &$this, 'enqueue_scripts' ) );
			
			// Load the appropriate styles if on the install page.
			add_action( 'admin_print_styles', array( &$this, 'enqueue_styles' ) );
			
			// Load user data from the database.
			add_action( 'admin_init', array( &$this, 'load_user_data' ) );
			
			// Setup the admin notice for when the login details aren't available.
			add_action ( 'admin_notices', array( &$this, 'admin_notice' ) );
			
			// Add contextual help to plugin-specific pages.
			add_action( 'contextual_help', array( &$this, 'contextual_help' ), 10, 3 );
			
			// Run "activation" when the plugin is activated.
			register_activation_hook( $this->plugin_base, array( &$this, 'activation' ) );
		
		} // End init()
		
		/*----------------------------------------
	 	  authenticate_user()
	 	  ----------------------------------------
	 	  
	 	  * Authenticate the user's login details.
	 	  
	 	  * Params:
	 	  * - String $user
	 	  * - String $pass
	 	----------------------------------------*/
		
		function authenticate_user ( $user, $pass ) {
		
			$is_valid = false;
			
			if ( $user != '' && $pass != '' ) {
				
				$params = array( 'username' => $user, 'password' => $pass, 'action' => 'authenticate' );
				
				$data = $this->get_api_data( $params );
				
				if ( $data != '' ) {
				
					$xmlobj = new SimpleXmlElement( $data );	
					
					if ( $xmlobj[0] == 'OK' ) {
					
						$is_valid = true;
						
						$this->username = $user;
						$this->password = $pass;
						
					} else {
					
						$is_valid = false;
					
					} // End IF Statement
				
				} // End IF Statement
				
			} // End IF Statement
			
			$this->is_valid = $is_valid;
			
			return $is_valid;
		
		} // End authenticate_user()
		
		/*----------------------------------------
	 	  load_user_data()
	 	  ----------------------------------------
	 	  
	 	  * Load user data from the database.
	 	----------------------------------------*/
		
		function load_user_data () {
		
			$user = get_option( $this->plugin_prefix . 'username' );
			$pass = get_option( $this->plugin_prefix . 'password' );
			
			$this->user = $user;
			$this->pass = $pass;
			
			// Authenticate the user's request.
			$this->authenticate_user( $user, $pass );
		
		} // End load_user_data()
		
		/*----------------------------------------
	 	  get_purchased_themes()
	 	  ----------------------------------------
	 	  
	 	  * Get all themes purchased by the user.
	 	----------------------------------------*/
		
		function get_purchased_themes ( $offset = 1, $limit = 10 ) {
		
			// Get the user's data from the database and authenticate it.
			$this->load_user_data();
		
			if ( $this->is_valid ) {
			
				$themes = array();
			
				$params = array( 'username' => $this->user, 'password' => $this->pass, 'action' => 'get_themes', 'offset' => $offset, 'limit' => $limit );
			
				$data = $this->get_api_data( $params );
				
				$xmlobj = new SimpleXmlElement( $data );
				
				if ( $xmlobj ) {
					
					// Extract the total number of themes.
					
					foreach( $xmlobj->attributes() as $k => $v ) {
					
						${$k} = $v;
						
						if ( $k == 'total' ) { $this->total_themes = $v; } // End IF Statement
					
					} // End FOREACH Loop
				
					foreach ( $xmlobj as $xml ) {
						
						// Generate a token for this theme.
						$_name_bits = explode( ' - ', $xml->name );
						
						$_key = urlencode( strtolower( $_name_bits[0] ) );
						
						$_key = str_replace( '+', '', $_key );
						
						$name_solo = $_name_bits[0];
						$package_type = $_name_bits[1];
						
						$_year = substr( $xml->launch_date, 0, 4 );
						$_month = substr( $xml->launch_date, 5, 2 );
						$_day = substr( $xml->launch_date, 8, 2 );
						
						$timestamp = date( "Y-m-d", mktime( 0, 0, 0, $_month, $_day, $_year ) );
						$date_formatted = date( "jS F Y", mktime( 0, 0, 0, $_month, $_day, $_year ) );
						
						$xml->addChild( 'timestamp', $timestamp );
						$xml->addChild( 'date_formatted', $date_formatted );
						$xml->addChild( 'name_solo', $name_solo );
						$xml->addChild( 'package', $package_type );
						$xml->addChild( 'css_class', $_key );
					
						$themes[$_key] = $xml;
						
						// Add the themes to a local array for working with in this class.
						$this->themes[$_key] = $xml;
					
					} // End FOREACH Loop
				
				} // End IF Statement
				
				// Add the themes to a local array for working with in this class.
				// $this->themes = $themes;
				
				return $themes;
			
			} // End IF Statement
		
		} // End get_purchased_themes()
		
		/*----------------------------------------
	 	  install_theme()
	 	  ----------------------------------------
	 	  
	 	  * Install the selected theme using the
	 	  * built in theme uploader in WordPress.
	 	  
	 	  * Params:
	 	  * - String $theme_key
	 	  * - Int $theme_id
	 	----------------------------------------*/
		
		function install_theme ( $theme_key, $theme_id ) {

			$_theme_data = $this->get_single_theme_data( $theme_id );
			
			if ( count( $_theme_data ) > 0 ) {
			
				$package = $this->get_theme_package( $_theme_data->id );
				
				if ( $package == '' ) {
				
					// Do nothing. The user isn't authorised to download and install this theme.
				
				} else {
				
					$_theme_url = $package;
					
					// $_theme_url = $package . '?username=' . $this->user . '&password=' . $this->pass;
				
					include_once ( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
					include_once ( ABSPATH . 'wp-admin/includes/theme-install.php' );
					
					// Get custom upgrader classes.
					include_once ( $this->plugin_path . '/classes/upgrader.class.php' );
					include_once ( $this->plugin_path . '/classes/upgrader-skin.class.php' );
					
					$woo_theme_install_skin = new Woo_Installer_Upgrade_Skin( compact('type', 'title', 'nonce', 'url') );
					$woo_theme_install = new Woo_Installer_Upgrader( $woo_theme_install_skin );
					
					$woo_theme_install->current_wootheme_name = $_theme_data->name_solo;
					
					// The folder this theme will be installed in.
					$this->installed_theme_key = $theme_key;
					$woo_theme_install_skin->installed_theme_key = $theme_key;
					
					// Install the theme.
					$woo_theme_install->install( $_theme_url );
				
				} // End IF Statement
			
			} // End IF Statement
		
		} // End install_theme()
		
		/*----------------------------------------
	 	  backup_existing_theme()
	 	  ----------------------------------------
	 	  
	 	  * Make a copy of an existing theme folder.
	 	  * Rename it by adding today's timestamp.
	 	  
	 	  * Params:
	 	  * - String $theme_folder
	 	  * - String $backup_type
	 	----------------------------------------*/
	 	
	 	function backup_existing_theme ( $theme_folder, $backup_type ) {
	 	
	 		$is_renamed = false;
	 		$allowed_backup_types = array( 'fresh-copy', 'overwrite' );
	 		
	 		// Only support our allowed backup types.
	 		if ( ! in_array( $backup_type, $allowed_backup_types ) ) {
	 		
	 			wp_die( _e( 'You are attempting to perform a backup type that is not supported. Please try again.', 'woothemes' ) ); exit;
	 		
	 		} // End IF Statement
	 	
	 		$themes_dir = trailingslashit( WP_CONTENT_DIR ) . 'themes/';
	 		
	 		switch ( $backup_type ) {
	 		
	 			// `fresh-copy` Backup type.
	 			case 'fresh-copy':
	 			
		 			if ( is_dir( $themes_dir . $theme_folder ) ) {
			 		
			 			/*
			 			// Create a unique timestamp for now.
			 			$timestamp = mktime( date('h'), date('i'), date('s'), date('d'), date('m'), date('Y') );
			 			
			 			// Create the new name of our new theme folder.
			 			$new_name = $theme_folder . '-backup-' . $timestamp;
			 			*/
			 			
			 			// Temporarily rename the current theme folder, to be "unrenamed" after the theme has been upgraded.
			 			$new_name = $theme_folder . '-current';
			 			
			 			// Rename our existing folder.
			 			$is_renamed = rename( $themes_dir . $theme_folder, $themes_dir . $new_name );
			 		
			 		} // End IF Statement
	 			
	 			break;
	 			
	 			// `overwrite` Backup type.
	 			case 'overwrite':
	 			
	 				if ( is_dir( $themes_dir . $theme_folder ) ) {
			 		
			 			// Create a unique timestamp for now.
			 			$timestamp = mktime( date('h'), date('i'), date('s'), date('d'), date('m'), date('Y') );
			 			
			 			// Create a variable containing the current date.
			 			$current_date = date('Y') . '-' . date('m') . '-' . date('d');
			 			
			 			// Create the new name of our new theme folder.
			 			$new_name = '_woo-installer-backups/' . $theme_folder . '-backup-' . $current_date . '-' . $timestamp;
			 			
			 			// If our backup directory doesn't exist, create it.
			 			if ( ! is_dir( $themes_dir . '_woo-installer-backups' ) ) {
			 			
			 				mkdir( $themes_dir . '_woo-installer-backups' );
			 			
			 			} // End IF Statement
			 			
			 			// Rename our existing folder.
			 			$is_renamed = rename( $themes_dir . $theme_folder, $themes_dir . $new_name );
			 		
			 		} // End IF Statement
	 			
	 			break;
	 		
	 		} // End SWITCH Statement
	 		
	 		return $is_renamed;
	 	
	 	} // End backup_existing_theme()
	 	
	 	/*----------------------------------------
	 	  upgrade_theme()
	 	  ----------------------------------------
	 	  
	 	  * Upgrade the selected theme using the
	 	  * built in theme uploader in WordPress.
	 	  
	 	  * Params:
	 	  * - String $theme_key
	 	  * - Int $theme_id
	 	----------------------------------------*/
		
		function upgrade_theme ( $theme_key, $theme_id ) {
			
			$is_upgraded = false;
			$current_version = 0;
			$theme_folder = $theme_key;
			$themes_dir = trailingslashit( WP_CONTENT_DIR ) . 'themes/';
			
			$is_new_renamed = false;
			$is_original_renamed = false;
					
			// Get the data for the theme entry passed via the API.
			$latest_theme_data = $this->get_single_theme_data( $theme_id );
			
			// If the latest theme data is available, get the theme's package via it's ID.
			if ( $latest_theme_data->id ) {
			
				$package = $this->get_theme_package( $latest_theme_data->id );
				
				include_once ( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
				include_once ( ABSPATH . 'wp-admin/includes/theme-install.php' );
				
				// Get custom upgrader classes.
				include_once ( $this->plugin_path . '/classes/upgrader.class.php' );
				include_once ( $this->plugin_path . '/classes/upgrader-skin.class.php' );
				
				$woo_theme_upgrade_skin = new Woo_Installer_Upgrade_Skin( compact('type', 'title', 'nonce', 'url') );
				$woo_theme_upgrade = new Woo_Installer_Upgrader( $woo_theme_upgrade_skin );
				
				$woo_theme_upgrade->current_wootheme_name = $theme_data->name_solo;
				
				$_theme_url = $package;
					
				// $_theme_url = $package . '?username=' . $this->user . '&password=' . $this->pass;
				
				// Upgrade the theme, if necessary.						
				$woo_theme_upgrade->install( $_theme_url );
				
				// Get the data for the theme we just installed.
				$installed_theme_data = get_theme_data( $themes_dir . $theme_folder . '/style.css' );
				
				$is_original_renamed = false;
				$is_new_renamed = false;
		 	
		 		// Rename the newly installed version to include it's version number.
		 	
		 		if ( is_dir( $themes_dir . $theme_folder ) ) {
		 			
		 			// Create the new name of our new theme folder.
		 			$new_name = $theme_folder . '-' . $installed_theme_data['Version'];
		 			
		 			// Let the Upgrader skin know the name of our new template that we just installed.
		 			// $woo_theme_upgrade->latest_wootheme = $new_name;
		 			
		 			// Rename our new theme folder.
		 			$is_new_renamed = rename( $themes_dir . $theme_folder, $themes_dir . $new_name );
		 		
		 		} // End IF Statement
		 		
		 		// Rename our previous instance back to it's original name.
		 		
		 		if ( $is_new_renamed && is_dir( $themes_dir . $theme_folder . '-current' ) ) {
		 			
		 			// Create the new name of our original theme folder.
		 			$new_name = $theme_folder;
		 			
		 			// Rename our original theme folder.
		 			$is_original_renamed = rename( $themes_dir . $theme_folder . '-current', $themes_dir . $new_name );
		 		
		 		} // End IF Statement
			
			} // End IF Statement
			
			return $is_original_renamed;
		
		} // End upgrade_theme()
		
		/*----------------------------------------
	 	  overwrite_theme()
	 	  ----------------------------------------
	 	  
	 	  * Overwrite the selected theme using the
	 	  * built in theme uploader in WordPress.
	 	  *
	 	  * The latest version is downloaded.
	 	  
	 	  * Params:
	 	  * - String $theme_key
	 	  * - Int $theme_id
	 	----------------------------------------*/
		
		function overwrite_theme ( $theme_key, $theme_id ) {
			
			$is_upgraded = false;
			$current_version = 0;
					
			// Get the data for the theme entry passed via the API.
			$latest_theme_data = $this->get_single_theme_data( $theme_id );
			
			// If the latest theme data is available, get the theme's package via it's ID.
			if ( $latest_theme_data->id ) {
			
				$package = $this->get_theme_package( $latest_theme_data->id );
				
				include_once ( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
				include_once ( ABSPATH . 'wp-admin/includes/theme-install.php' );
				
				// Get custom upgrader classes.
				include_once ( $this->plugin_path . '/classes/upgrader.class.php' );
				include_once ( $this->plugin_path . '/classes/upgrader-skin.class.php' );
				
				$woo_theme_upgrade_skin = new Woo_Installer_Upgrade_Skin( compact('type', 'title', 'nonce', 'url') );
				$woo_theme_upgrade = new Woo_Installer_Upgrader( $woo_theme_upgrade_skin );
				
				$woo_theme_upgrade->current_wootheme_name = $theme_data->name_solo;
				
				$_theme_url = $package;
					
				// $_theme_url = $package . '?username=' . $this->user . '&password=' . $this->pass;
				
				// Upgrade the theme, if necessary.						
				$woo_theme_upgrade->install( $_theme_url );
				
				$is_upgraded = true;
			
			} // End IF Statement
			
			return $is_upgraded;
		
		} // End overwrite_theme()
		
		/*----------------------------------------
	 	  check_for_upgrade()
	 	  ----------------------------------------
	 	  
	 	  * Check if an upgrade is available
	 	  * for a particular theme.
	 	  
	 	  * Params:
	 	  * - String $theme_key
	 	  * - Int $theme_id
	 	  * - String $current_version
	 	----------------------------------------*/
	 	
	 	function check_for_upgrade ( $theme_key, $theme_id, $current_version ) {
	 	
	 		$is_upgrade = 0;

	 		$latest_version = 0;
				
				if ( $current_version != '' ) {
				
					// $current_version = $theme_data['Version'];
					
					$latest_data = get_theme_data( $this->demo_url . $theme_key . '/style.css' );
					
					if ( count( $latest_data ) > 0 ) {
					
						$latest_version = $latest_data['Version'];
						
						if ( $latest_version > $current_version ) {
						
							$is_upgrade = $latest_version;
						
						} // End IF Statement
					
					} // End IF Statement
					
				} // End IF Statement
	 		
	 		return $is_upgrade;
	 	
	 	} // End check_for_upgrade()
		
		/*----------------------------------------
	 	  Utility Functions
	 	  ----------------------------------------
	 	  
	 	  * These functions are used within this
	 	  * class as helpers for other functions.
	 	----------------------------------------*/
	 	
	 	/*----------------------------------------
	 	  register_nav_menu_link()
	 	  ----------------------------------------
	 	  
	 	  * Add our new navigation menu item
	 	  * under the "Appearance" tab in the
	 	  * WordPress admin menu.
	 	----------------------------------------*/
		
		function register_nav_menu_link () {
		
			if (function_exists('add_submenu_page')) {
				
				$this->admin_screen = add_submenu_page('themes.php', __( 'Install WooThemes', 'woothemes' ), __( 'Install WooThemes', 'woothemes' ), 'switch_themes', $this->plugin_path, array( &$this, 'load_admin_screen' ) );
				
				$this->admin_screen = str_replace( 'appearance_page_', '', $this->admin_screen );
				
				// Make sure the installation and upgrade URLs reflect the correct WordPress admin screen.
				
				$this->install_url = str_replace( 'woo-installer', $this->admin_screen, $this->install_url );
	 			$this->upgrade_url = str_replace( 'woo-installer', $this->admin_screen, $this->upgrade_url );
				
			} // End IF Statement
		
		} // End register_nav_menu_link()
		
		/*----------------------------------------
	 	  load_admin_screen()
	 	  ----------------------------------------
	 	  
	 	  * Load the appropriate admin screen.
	 	----------------------------------------*/
		
		function load_admin_screen () {
		
			// Check for the presence of the cURL extension, which we require.
			
			if ( $this->is_curl_installed() ) {
		
				if (
				
				isset( $_POST ) && 
				isset( $_POST['username'] ) && 
				isset( $_POST['password'] ) && 
				isset( $_POST['woo-action'] ) && 
				$_POST['woo-action'] == 'login'
				
				) {
				
					$user = trim( strip_tags( $_POST['username'] ) );
					$pass = md5( trim( strip_tags( $_POST['password'] ) ) );
					
					// Authenticate the user's request.
					$this->authenticate_user( $user, $pass );
					
					if ( $this->is_valid ) {
					
						update_option( $this->plugin_prefix . 'username', $user );
						update_option( $this->plugin_prefix . 'password', $pass );
						
						$this->user = $user;
						$this->pass = $pass;
					
					} else {
					
						echo '<div class="error fade"><p>' . __( 'The login details supplied are invalid. Please try again.', 'woothemes' ) . '</p></div>' . "\n";
					
					} // End IF Statement
				
				} // End IF Statement
			
				// If the user is valid...
				if ( $this->is_valid ) {
				
					// If the user is installing a theme...
					if ( $this->is_installing && $_GET['woo-action'] == 'install-theme' ) {
					
						require_once( $this->plugin_path . '/screens/install-theme.php' );
					
					// If the user is upgrading a theme...
					} else if ( $this->is_upgrading && ( $_GET['woo-action'] == 'upgrade-theme' || $_GET['woo-action'] == 'upgrade-theme-overwrite' ) ) {
					
						require_once( $this->plugin_path . '/screens/upgrade-theme.php' );
					
					// If the user is attempting to switch to another WooThemes account...
					} else if ( isset( $_GET['woo-action'] ) && $_GET['woo-action'] == 'switch-account' ) {
					
						$this->is_account_switch = true;
					
						$title = __( 'Switch WooThemes Account', 'woothemes' );
						
						$url_params = '&amp;woo-action=switch-account';
						
						$button_text = __( 'Switch Account', 'woothemes' );
					
						// Separate the admin page XHTML to keep things neat and in the appropriate location.
						require_once( $this->plugin_path . '/screens/login.php' );
						
					// Otherwise, show the "Themes" grid...
					} else {
					
						// Separate the admin page XHTML to keep things neat and in the appropriate location.
						require_once( $this->plugin_path . '/screens/themes.php' );
						
					} // End IF Statement
	
				// Otherwise, show the user login screen...
				} else {
				
					$title = __( 'WooThemes Account Login', 'woothemes' );
					
					$url_params = '';
					
					$button_text = __( 'Login', 'woothemes' );
					
					// Separate the admin page XHTML to keep things neat and in the appropriate location.
					require_once( $this->plugin_path . '/screens/login.php' );
				
				} // End IF Statement

			} else {
			
				// The cURL extension isn't available. Let the user know.
				
				// Separate the admin page XHTML to keep things neat and in the appropriate location.
				require_once( $this->plugin_path . '/screens/error_curl.php' );
			
			} // End IF Statement

		} // End load_admin_screen()
		
		/*----------------------------------------
	 	  load_installer()
	 	  ----------------------------------------
	 	  
	 	  * Load the install screen or theme
	 	  * installer, depending on action.
	 	----------------------------------------*/
		
		function load_installer () {

			if ( is_admin() && isset( $_GET['woo-action'] ) ) {
			
				$_action = strtolower( strip_tags( $_GET['woo-action'] ) );
			
				switch ( $_action ) {
				
					case 'install-confirmation':
					
						require_once( $this->plugin_path . '/screens/install-confirmation.php' );
						
						die;
					
					break;
					
					case 'upgrade-confirmation':
					
						require_once( $this->plugin_path . '/screens/upgrade-confirmation.php' );
						
						die;
					
					break;
					
					case 'install-theme':
					
						$_theme_key = strtolower( strip_tags( $_GET['theme-key'] ) );
					
						$this->is_installing = true;
						$this->theme_to_install = $_theme_key;
						
					break;
					
					case 'upgrade-theme':
					
						$_theme_key = strtolower( strip_tags( $_GET['theme-key'] ) );
					
						$this->is_upgrading = true;
						$this->theme_to_install = $_theme_key;
						
					break;
					
					case 'upgrade-theme-overwrite':
					
						$_theme_key = strtolower( strip_tags( $_GET['theme-key'] ) );
					
						$this->is_upgrading = true;
						$this->theme_to_install = $_theme_key;
						
					break;

					
				} // End SWITCH Statement
			
			} // End IF Statement
		
		} // End load_install_screen()
		
		/*----------------------------------------
	 	  get_api_data()
	 	  ----------------------------------------
	 	  
	 	  * Return the contents of a URL
	 	  * using cURL.
	 	  
	 	  * Params:
	 	  * Array - $params
	 	----------------------------------------*/
		
		function get_api_data ( $params = array() ) {
		
			$ch = curl_init( $this->api_url );
			$encoded = '';
			// include GET as well as POST variables; your needs may vary.
			/*
			foreach($_GET as $name => $value) {
				$encoded .= urlencode($name).'='.urlencode($value).'&';
			}
			*/
			
			/*
			foreach($_POST as $name => $value) {
				$encoded .= urlencode($name).'='.urlencode($value).'&';
			}
			*/
			
			// chop off last ampersand
			// $encoded = substr($encoded, 0, strlen($encoded)-1);
			
			if ( count( $params ) ) {
			
				foreach ( $params as $k => $v ) {
				
					$encoded .= urlencode( $k ) . '=' . urlencode( $v ) . '&';
				
				} // End FOREACH Loop
				
				// chop off last ampersand
				$encoded = substr( $encoded, 0, strlen( $encoded )-1 );
			
			} // End IF Statement
			
			curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($ch);
			curl_close($ch);
			
			return $data;
		
		} // End get_api_data()
		
		/*----------------------------------------
	 	  remote_file_exists()
	 	  ----------------------------------------
	 	  
	 	  * Check if a remote file exists.
	 	  
	 	  * Params:
	 	  * String - $remote_file
	 	  
	 	  * Return:
	 	  * Boolean - $file_exists
	 	----------------------------------------*/
		
		function remote_file_exists ( $remote_file ) {
		
			$file_exists = false;
		
			if ( $remote_file == '' ) { return '400'; } // End IF Statement
		
			$ch = curl_init( $remote_file );
			
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			// $retcode > 400 -> not found, $retcode = 200, found.
			curl_close($ch);
			
			if ( $retcode == '200' ) {
			
				$file_exists = true;
			
			} // End IF Statement
			
			return $file_exists;
		
		} // End remote_file_exists()
		
		/*----------------------------------------
	 	  get_theme_by_key()
	 	  ----------------------------------------
	 	  
	 	  * Get theme data from the internal array.
	 	  
	 	  * Params:
	 	  * String - $token
	 	  * Array - $themes
	 	  
	 	  * Return:
	 	  * Array - $theme
	 	----------------------------------------*/
		
		function get_theme_by_key ( $token, $themes ) {
		
			if ( ! $token || $token == '' ) { return; } // End IF Statement
		
			$theme_data = array();
			
			// $data = $this->themes[$token];
			
			$data = $themes;
			
			if ( $data ) {
			
				$theme_data = $data;
			
			} // End IF Statement
			
			return $theme_data;
		
		} // End get_theme_by_key()
		
		/*----------------------------------------
	 	  get_single_theme_data()
	 	  ----------------------------------------
	 	  
	 	  * Get the data for a theme.
	 	  
	 	  * Params:
	 	  * Int - $theme_id
	 	  
	 	  * Return:
	 	  * Array - $theme_data
	 	----------------------------------------*/
		
		function get_single_theme_data ( $theme_id ) {
		
			$this->load_user_data();
		
			// Make sure the theme ID is an integer and not an XML element.
			$_theme_id = $theme_id;
			
			if ( ! is_numeric( $_theme_id ) ) { return; } // End IF Statement
			
			$theme_data = '';
			
			$params = array( 'username' => $this->user, 'password' => $this->pass, 'theme_id' => $_theme_id, 'action' => 'get_theme' );
			
			$data = $this->get_api_data( $params );
			
			$xmlobj = new SimpleXmlElement( $data );
			
			if ( $xmlobj->status ) {} else {
			
				foreach ( $xmlobj->theme as $xml ) {
				
					if ( $theme_data == '' ) {
				
						// Generate a token for this theme.
						$_name_bits = explode( ' - ', $xml->name );
						
						$_key = urlencode( strtolower( $_name_bits[0] ) );
						
						$_key = str_replace( '+', '_', $_key );
						
						$name_solo = $_name_bits[0];
						$package_type = $_name_bits[1];
						
						$_year = substr( $xml->launch_date, 0, 4 );
						$_month = substr( $xml->launch_date, 5, 2 );
						$_day = substr( $xml->launch_date, 8, 2 );
						
						$timestamp = date( "Y-m-d", mktime( 0, 0, 0, $_month, $_day, $_year ) );
						$date_formatted = date( "jS F Y", mktime( 0, 0, 0, $_month, $_day, $_year ) );
						
						$xml->addChild( 'timestamp', $timestamp );
						$xml->addChild( 'date_formatted', $date_formatted );
						$xml->addChild( 'name_solo', $name_solo );
						$xml->addChild( 'package', $package_type );
						$xml->addChild( 'css_class', $_key );
						
						$theme_data = $xml;
				
					} // End IF Statement
				
				} // End FOREACH Loop
				
			} // End IF Statement
			
			return $theme_data;
		
		} // End get_single_theme_data()
		
		/*----------------------------------------
	 	  get_theme_package()
	 	  ----------------------------------------
	 	  
	 	  * Get the theme package via the API.
	 	  
	 	  * Params:
	 	  * Int - $theme_id
	 	  
	 	  * Return:
	 	  * String - $_theme_url
	 	----------------------------------------*/
		
		function get_theme_package ( $theme_id ) {
		
			// Make sure the theme ID is an integer and not an XML element.
			$_theme_id = (int) $theme_id;
		
			if ( ! is_numeric( $_theme_id ) ) { return; } // End IF Statement
			
			$_theme_url = '';
			
			$params = array( 'username' => $this->user, 'password' => $this->pass, 'theme_id' => $_theme_id, 'action' => 'send_theme' );
			
			$data = $this->get_api_data( $params );
			
			$xmlobj = new SimpleXmlElement( $data );
			
			if ( $xmlobj->status ) {
			
				// Not authorised to download this theme. Do nothing.
			
			} else {
			
				$_theme_url = $xmlobj->theme->url;
				
				$_theme_url = urldecode( $_theme_url );
				
				$concatenator = '?';
				
				$pos = strpos( $_theme_url, '?' );
				
				if ( $pos === false ) {} else {
				
					$concatenator = '&';
				
				} // End IF Statement
				
				$_theme_url .= $concatenator . 'username=' . $this->user . '&password=' . $this->pass;
				
			} // End IF Statement
			
			return $_theme_url;
		
		} // End get_theme_package()
		
		/*----------------------------------------
	 	  enqueue_scripts()
	 	  ----------------------------------------
	 	  
	 	  * Load the necessary JavaScript for
	 	  * the installer popup window.
	 	----------------------------------------*/
		
		function enqueue_scripts () {
		
			if ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'woo-installer' ) {

				wp_enqueue_script( 'theme-install' );
				
				add_thickbox();
				wp_enqueue_script( 'theme-preview' );
				
			} // End IF Statement
		
		} // End enqueue_scripts()
		
		/*----------------------------------------
	 	  enqueue_styles()
	 	  ----------------------------------------
	 	  
	 	  * Load the necessary CSS for
	 	  * the installer popup window.
	 	----------------------------------------*/
		
		function enqueue_styles () {
		
			if ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'woo-installer' ) {
				
				wp_enqueue_style( 'thickbox' );
				
				wp_enqueue_style( 'theme-install' );
				
			} // End IF Statement
		
		} // End enqueue_styles()
		
		/*----------------------------------------
	 	  admin_notice()
	 	  ----------------------------------------
	 	  
	 	  * Load the admin notice if necessary.
	 	----------------------------------------*/
	
		function admin_notice () {
		
			$missing_options = array();
			$options = array( 'username', 'password' );
			
			foreach ( $options as $o) {
	
				if ( get_option( $this->plugin_prefix . $o ) == '' ) {
				
					$missing_options = $o;
					
				} // End IF Statement
				
			} // End FOREACH Loop
			
			if ( ( count( $missing_options ) > 0 || ! $this->is_valid ) && $_GET['page'] != 'woo-installer' ) {
			
				$notice = '';
				$notice .= '<div id="woo-installer-notice" class="updated fade">' . "\n";
				$notice .= '<p><strong>' . __( 'WooInstaller is almost ready.', 'woothemes' ) . '</strong> ' . "\n";
				
				$notice .= sprintf( __( 'Please <a href="%1$s">run the setup</a> for it to work.', 'woothemes' ), 'themes.php?page=' . $this->admin_screen );
				$notice .= "\n" . '</p>' . "\n";
				
				$notice .= '</div>' . "\n";
				
				echo $notice;
			
			} // End IF Statement
			
			// Display a notice when the user has requested to switch their account.
			if ( isset( $_GET['woo-action'] ) && $_GET['woo-action'] == 'switch-account' ) {
			
				$notice = '';
				$notice .= '<div id="woo-installer-notice" class="updated fade">' . "\n";
				$notice .= '<p><strong>' . __( 'Switch to another WooThemes account.', 'woothemes' ) . '</strong> ' . "\n";
				
				$notice .= __( 'Please fill in your login details below to switch to another WooThemes account.', 'woothemes' );
				$notice .= "\n" . '</p>' . "\n";
				
				$notice .= '</div>' . "\n";
				
				echo $notice;
			
			} // End IF Statement
		
		} // End admin_notice()
		
		/*----------------------------------------
	 	  contextual_help()
	 	  ----------------------------------------
	 	  
	 	  * Load contextual help.
	 	----------------------------------------*/
	
		function contextual_help ( $contextual_help, $screen_id, $screen ) { 
		  
			  // $contextual_help .= var_dump($screen); // use this to help determine $screen->id
			  
			  if ( 'appearance_page_woo-installer' == $screen->id ) {
			  
			    $contextual_help =
			      '<p>' . __( 'Welcome to WooInstaller, Your one-stop shop for installing your favourite WooThemes!', 'woothemes' ) . '</p>' .
			      '<ul>' .
			      '<li>' . __( 'If your WooThemes login details aren\'t stored, WooInstaller will ask you for them (you should only need to do this once).', 'woothemes' ) . '</li>' .
			      '<li>' . __( 'Once we have your login details stored, WooInstaller will display all the WooThemes you\'ve purchased, with easy-install links for each theme.', 'woothemes' ) . '</li>' .
			      '<li>' . __( 'Click install and hey presto! Your new WooTheme is installed and ready to run.', 'woothemes' ) . '</li>' .
			      '</ul>' .
			      '<p><strong>' . __( 'For more information:', 'woothemes' ) . '</strong></p>' .
			      '<p>' . __( '<a href="http://forum.woothemes.com/" target="_blank">WooThemes Support Forums</a>', 'woothemes' ) . '</p>';
			  
			  } // End IF Statement
			  
			  return $contextual_help;
		  
		  } // End contextual_help()
		
		/*----------------------------------------
	 	  activation()
	 	  ----------------------------------------
	 	  
	 	  * Runs when the plugin is activated.
	 	----------------------------------------*/
	 	
	 	function activation () {
	 		
	 		$data = get_plugin_data( WP_PLUGIN_DIR . '/' . $this->plugin_base );
	 		
	 		update_option( $this->plugin_prefix . 'version', $data['Version'] );
	 	
	 	} // End activation()
	 	
	 	/*----------------------------------------
	 	  update()
	 	  ----------------------------------------
	 	  
	 	  * Runs when the plugin is updated.
	 	----------------------------------------*/
	 	
	 	function update () {} // End update()
	
		/*----------------------------------------
	 	  pagination_links()
	 	  ----------------------------------------
	 	  
	 	  * Generate pagination links for the
	 	  * themes grid.
	 	  
	 	  * Params:
	 	  * Int - $total
	 	  * Int - $current_page
	 	  * Int - $per_page
	 	----------------------------------------*/
	 	
	 	function pagination_links ( $total, $current_page = 1, $per_page = 10 ) {
	 	
	 		$_html = '';
	 	
	 		// Don't show the pagination if there isn't going to be more than 1 page.
	 		if ( $total <= $per_page ) { return; } // End IF Statement
	 	
	 		$start = 1;
			$end = $per_page;
			$num_pages = ceil( $total / $per_page );
	 	
	 		// Work out the starting offset and ending number.
	 		if ( $current_page > 1 ) {
	 		
	 			$start = ( ( $per_page * $current_page ) - $per_page ) + 1;
	 			$end = ( $start + $per_page ) - 1;
	 			
	 			if ( $start > $total ) { $start = 1; } // End IF Statement
	 			if ( $end > $total ) { $end = $total; } // End IF Statement
	 		
	 		} // End IF Statement
	 	
	 		$page_links = paginate_links( array(
				'base' => add_query_arg( 'pagenum', '%#%' ),
				'format' => '',
				'prev_text' => __('&laquo;'),
				'next_text' => __('&raquo;'),
				'total' => ceil($total / $per_page),
				'current' => $current_page
			));
			
			$_html .= '<div class="tablenav">' . "\n";
				$_html .= '<div class="tablenav-pages">' . "\n";
					$_html .= '<span class="displaying-num">';
						$_html .= sprintf( __( 'Displaying %d-%d of %d', 'woothemes' ), $start, $end, $total );
					$_html .= '</span>' . "\n";
			
					$_html .= $page_links;
					
				$_html .= '</div><!--/.tablenav-pages-->' . "\n";
			$_html .= '</div><!--/.tablenav-->' . "\n";
			
			echo $_html;
	 	
	 	} // End pagination_links()
	 	
	 	/*----------------------------------------
	 	  is_curl_installed()
	 	  ----------------------------------------
	 	  
	 	  * Checks for the presence of the
	 	  * cURL extension.
	 	----------------------------------------*/
	 	
	 	function is_curl_installed () {
	 	
			if  ( in_array( 'curl', get_loaded_extensions() ) ) {
			
				if ( function_exists('curl_init') ) {
				
					return true;
					
				} else {
				
					return false;
					
				} // End IF Statement
				
			} else {
			
				if ( function_exists('curl_init') ) {
				
					return true;
					
				} else {
				
					return false;
					
				} // End IF Statement
				
			} // End IF Statement
		
		} // End is_curl_installed()
	
	} // End Class
?>