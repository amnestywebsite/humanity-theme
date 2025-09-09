<?php

declare( strict_types = 1 );

if ( ! function_exists( 'modify_user_contact_methods' ) ) {
	/**
	 * Adds a custom field for Twitter handles in user profiles
	 *
	 * @package Amnesty\Users
	 *
	 * @param array $user_contact List of contact methods
	 *
	 * @return array
	 */
	function modify_user_contact_methods( array $user_contact ) {
		/* translators: [admin] user settings */
		$user_contact['twitter'] = __( 'Twitter Username (without the @)', 'amnesty' );

		return $user_contact;
	}
}

add_filter( 'user_contactmethods', 'modify_user_contact_methods' );
