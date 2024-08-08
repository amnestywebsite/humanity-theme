<div class="wrap">
	<h1><?php echo wp_kses_post( get_admin_page_title() ); ?></h1>

	<?php settings_errors( \Amnesty\Options_Manager::$option_key ); ?>

	<form action="<?php menu_page_url( \Amnesty\Options_Manager::$menu_slug ); ?>" method="post">
		<?php wp_nonce_field( \Amnesty\Options_Manager::$menu_slug . '_submit' ); ?>

		<?php

		settings_fields( \Amnesty\Options_Manager::$option_key );
		do_settings_sections( \Amnesty\Options_Manager::$option_key );
		submit_button();

		?>
	</form>
</div>
