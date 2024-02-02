<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_create_popin_posttype' ) ) {
	/**
	 * Register pop-in post-type.
	 *
	 * @package Amnesty\PostTypes
	 *
	 * @return void
	 */
	function amnesty_create_popin_posttype() {
		if ( ! amnesty_feature_is_enabled( 'pop-in' ) ) {
			return;
		}

		$labels = [
			/* translators: [admin] */
			'name'                  => _x( 'Pop-in variants', 'Post Type General Name', 'amnesty' ),
			/* translators: [admin] */
			'singular_name'         => _x( 'Pop-in', 'Post Type Singular Name', 'amnesty' ),
			/* translators: [admin] */
			'menu_name'             => __( 'Pop-in variants', 'amnesty' ),
			/* translators: [admin] */
			'name_admin_bar'        => __( 'Pop-in', 'amnesty' ),
			/* translators: [admin] */
			'archives'              => __( 'Pop-in Archives', 'amnesty' ),
			/* translators: [admin] */
			'attributes'            => __( 'Pop-in Attributes', 'amnesty' ),
			/* translators: [admin] */
			'parent_item_colon'     => __( 'Parent Pop-in:', 'amnesty' ),
			/* translators: [admin] */
			'all_items'             => __( 'All Pop-in variants', 'amnesty' ),
			/* translators: [admin] */
			'add_new_item'          => __( 'Add New Pop-in variant', 'amnesty' ),
			/* translators: [admin] */
			'add_new'               => __( 'Add New', 'amnesty' ),
			/* translators: [admin] */
			'new_item'              => __( 'New Pop-in', 'amnesty' ),
			/* translators: [admin] */
			'edit_item'             => __( 'Edit Pop-in', 'amnesty' ),
			/* translators: [admin] */
			'update_item'           => __( 'Update Pop-in', 'amnesty' ),
			/* translators: [admin] */
			'view_item'             => __( 'View Pop-in', 'amnesty' ),
			/* translators: [admin] */
			'view_items'            => __( 'View Pop-in variants', 'amnesty' ),
			/* translators: [admin] */
			'search_items'          => __( 'Search Pop-in variants', 'amnesty' ),
			/* translators: [admin] */
			'not_found'             => __( 'No pop-in variants found', 'amnesty' ),
			/* translators: [admin] */
			'not_found_in_trash'    => __( 'No pop-in variants found in Trash', 'amnesty' ),
			/* translators: [admin] */
			'featured_image'        => __( 'Featured Image', 'amnesty' ),
			/* translators: [admin] */
			'set_featured_image'    => __( 'Set featured image', 'amnesty' ),
			/* translators: [admin] */
			'remove_featured_image' => __( 'Remove featured image', 'amnesty' ),
			/* translators: [admin] */
			'use_featured_image'    => __( 'Use as featured image', 'amnesty' ),
			/* translators: [admin] */
			'insert_into_item'      => __( 'Insert into pop-in', 'amnesty' ),
			/* translators: [admin] */
			'uploaded_to_this_item' => __( 'Uploaded to this pop-in', 'amnesty' ),
			/* translators: [admin] */
			'items_list'            => __( 'Pop-in variants list', 'amnesty' ),
			/* translators: [admin] */
			'items_list_navigation' => __( 'Pop-in variants list navigation', 'amnesty' ),
			/* translators: [admin] */
			'filter_items_list'     => __( 'Filter pop-in variants', 'amnesty' ),
		];

		$args = [
			/* translators: [admin] */
			'label'               => __( 'Pop-in', 'amnesty' ),
			/* translators: [admin] */
			'description'         => __( 'A Pop-in is a call to action which renders at the very top of a page, pushing the entire page content down.', 'amnesty' ),
			'labels'              => $labels,
			'supports'            => [ 'title', 'editor', 'custom-fields' ],
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 45,
			'menu_icon'           => 'dashicons-megaphone',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
			'rewrite'             => false,
			'codename'            => 'pop-in',
		];

		register_post_type( 'pop-in', $args );

		register_meta(
			'post',
			'renderTitle',
			[
				'auth_callback'  => fn () => current_user_can( 'edit_posts' ),
				'object_subtype' => 'pop-in',
				'type'           => 'string',
				'default'        => 'yes',
				'show_in_rest'   => true,
				'single'         => true,
			]
		);
	}
}

add_action( 'init', 'amnesty_create_popin_posttype', 0 );
