<?php

declare( strict_types = 1 );

add_action( 'cmb2_admin_init', 'author_register_user_profile_metabox' );

if ( ! function_exists( 'author_register_user_profile_metabox' ) ) {
	/**
	 * Hook in and add a metabox to add fields to the user profile pages
	 *
	 * @package Amnesty\Plugins\CMB2
	 *
	 * @return void
	 */
	function author_register_user_profile_metabox() {
		$prefix = 'author';

		/**
		 * Metabox for the user profile screen
		 */
		$cmb_user = new_cmb2_box(
			[
				'id'               => $prefix . 'edit',
				/* translators: [admin] */
				'title'            => __( 'User Profile Metabox', 'cmb2' ), // Doesn't output for user boxes
				'object_types'     => [ 'user' ], // Tells CMB2 to use user_meta vs post_meta
				'show_names'       => true,
				'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
			] 
		);

		$cmb_user->add_field(
			[
				/* translators: [admin] */
				'name'     => __( 'Author Page Info', 'cmb2' ),
				/* translators: [admin] */
				'desc'     => __( 'This information will be used for your personal author page', 'cmb2' ),
				'id'       => $prefix . '_page_info',
				'type'     => 'title',
				'on_front' => false,
			] 
		);

		$cmb_user->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Avatar', 'cmb2' ),
				/* translators: [admin] */
				'desc' => __( 'This will be displayed as your profile picture', 'cmb2' ),
				'id'   => $prefix . 'avatar',
				'type' => 'file',
			] 
		);

		$cmb_user->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Banner Image', 'cmb2' ),
				/* translators: [admin] */
				'desc' => __( 'This will be displayed as a header', 'cmb2' ),
				'id'   => $prefix . 'banner',
				'type' => 'file',
			] 
		);

		$cmb_user->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Description', 'cmb2' ),
				/* translators: [admin] */
				'desc' => __( 'This should be a short sentence about yourself to be displayed on your profile page', 'cmb2' ),
				'id'   => $prefix . 'descriptionsection',
				'type' => 'wysiwyg',
			] 
		);

		$cmb_user->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Biography', 'cmb2' ),
				/* translators: [admin] */
				'desc' => __( 'This can be a longer biography which will be used for users to learn more about you', 'cmb2' ),
				'id'   => $prefix . 'biographysection',
				'type' => 'wysiwyg',
			] 
		);
	}
}
