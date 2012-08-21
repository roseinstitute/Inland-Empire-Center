<?php
	$_theme_key = strtolower( strip_tags( $_GET['theme-key'] ) );
	$_theme_id = (int) $_GET['theme_id'];
	
	if ( $_theme_key == '' || ! is_numeric( $_theme_id ) ) {
	
		// DO NOTHING
	
	} else {
	
		$this->install_theme( $_theme_key, $_theme_id );
	
	} // End IF Statement
?>