<?php

declare( strict_types = 1 );

add_action(
	'wp_footer',
	function () {
		ob_start();
	},
	PHP_INT_MIN 
);

add_action(
	'wp_footer',
	function () {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo str_replace(
			"'wordpress_*','wordpress_logged_in_*','CookieControl',",
			"'*ordpress_*','*ordpress_logged_in_*','CookieControl',",
			ob_get_clean()
		);
	},
	PHP_INT_MAX 
);
