<?php
	$_theme_key = strtolower( strip_tags( $_GET['theme-key'] ) );
	$_theme_id = (int) $_GET['theme_id'];
	$_action = $_GET['woo-action'];
	
	if ( $_theme_key == '' || ! is_numeric( $_theme_id ) ) {
	
		// DO NOTHING
	
	} else {
		
		$_backup_type = 'fresh-copy';
		
		if ( $_action == 'upgrade-theme-overwrite' ) {
		
			$_backup_type = 'overwrite';
		
		} // End IF Statement
		
		// Make a backup of the existing theme folder, to preserve any custom changes.
		$is_renamed = $this->backup_existing_theme( $_theme_key, $_backup_type );
		
		if ( $is_renamed ) {
		
			switch ( $_backup_type ) {
			
				// `fresh-copy` Backup type.
	 			case 'fresh-copy':
	 			
		 			$this->upgrade_theme( $_theme_key, $_theme_id );
	 			
	 			break;
	 			
	 			// `overwrite` Backup type.
	 			case 'overwrite':
	 			
	 				$this->overwrite_theme( $_theme_key, $_theme_id );
	 			
	 			break;
			
			} // End SWITCH Statement
		
		} else {
		
			echo sprintf ( __( 'There was an error while backing up your existing copy of %s.', 'woothemes' ), '<code>' . $_theme_key . '</code>' );
		
		} // End IF Statement
	
	} // End IF Statement
?>