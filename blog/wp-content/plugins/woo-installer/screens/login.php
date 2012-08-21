<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php echo $title; ?></h2>
	
	<form name="woo-installer-login" id="woo-installer-login" action="<?php echo admin_url( 'themes.php?page=' . $this->admin_screen . $url_params ); ?>" method="post">
		<fieldset>
			<table class="form-table">
				<tbody>
			<?php /*<legend><?php _e( 'Login to your WooThemes account', 'woothemes' ); ?></legend>*/ ?>
					<tr>
						<th scope="row"><label for="username"><?php _e( 'WooThemes Username', 'woothemes' ); ?>:</label></th>
						<td><input type="text" class="input-text input-woo_user regular-text" name="username" id="woo_user" value="" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="password"><?php _e( 'WooThemes Password', 'woothemes' ); ?>:</label></th>
						<td><input type="password" class="input-text input-woo_pass regular-text" name="password" id="woo_pass" value="" /></td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		
		<fieldset>
			<p class="submit">
				<button type="submit" name="woo_login" id="woo_login" class="button-primary"><?php echo $button_text; ?></button>
			</p>
			<input type="hidden" name="woo-action" value="login" />
			<input type="hidden" name="page" value="woo-installer" />
		</fieldset>
	</form>

</div><!--/.wrap-->