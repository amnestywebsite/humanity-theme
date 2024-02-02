<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_create_sidebar_posttype' ) ) {
	/**
	 * Register sidebar post-type.
	 *
	 * @package Amnesty\PostTypes
	 *
	 * @return void
	 */
	function amnesty_create_sidebar_posttype() {
		$labels = [
			/* translators: [admin] */
			'name'                  => _x( 'Sidebars', 'Post Type General Name', 'amnesty' ),
			/* translators: [admin] */
			'singular_name'         => _x( 'Sidebar', 'Post Type Singular Name', 'amnesty' ),
			/* translators: [admin] */
			'menu_name'             => __( 'Sidebars', 'amnesty' ),
			/* translators: [admin] */
			'name_admin_bar'        => __( 'Sidebar', 'amnesty' ),
			/* translators: [admin] */
			'archives'              => __( 'Sidebar Archives', 'amnesty' ),
			/* translators: [admin] */
			'attributes'            => __( 'Sidebar Attributes', 'amnesty' ),
			/* translators: [admin] */
			'parent_item_colon'     => __( 'Parent Sidebar:', 'amnesty' ),
			/* translators: [admin] */
			'all_items'             => __( 'All Sidebars', 'amnesty' ),
			/* translators: [admin] */
			'add_new_item'          => __( 'Add New Sidebar', 'amnesty' ),
			/* translators: [admin] */
			'add_new'               => __( 'Add New', 'amnesty' ),
			/* translators: [admin] */
			'new_item'              => __( 'New Sidebar', 'amnesty' ),
			/* translators: [admin] */
			'edit_item'             => __( 'Edit Sidebar', 'amnesty' ),
			/* translators: [admin] */
			'update_item'           => __( 'Update Sidebar', 'amnesty' ),
			/* translators: [admin] */
			'view_item'             => __( 'View Sidebar', 'amnesty' ),
			/* translators: [admin] */
			'view_items'            => __( 'View Sidebars', 'amnesty' ),
			/* translators: [admin] */
			'search_items'          => __( 'Search Sidebar', 'amnesty' ),
			/* translators: [admin] */
			'not_found'             => __( 'Not Found', 'amnesty' ),
			/* translators: [admin] */
			'not_found_in_trash'    => __( 'Not Found in Trash', 'amnesty' ),
			/* translators: [admin] */
			'featured_image'        => __( 'Featured Image', 'amnesty' ),
			/* translators: [admin] */
			'set_featured_image'    => __( 'Set featured image', 'amnesty' ),
			/* translators: [admin] */
			'remove_featured_image' => __( 'Remove featured image', 'amnesty' ),
			/* translators: [admin] */
			'use_featured_image'    => __( 'Use as featured image', 'amnesty' ),
			/* translators: [admin] */
			'insert_into_item'      => __( 'Insert into sidebar', 'amnesty' ),
			/* translators: [admin] */
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'amnesty' ),
			/* translators: [admin] */
			'items_list'            => __( 'Sidebars list', 'amnesty' ),
			/* translators: [admin] */
			'items_list_navigation' => __( 'Sidebars list navigation', 'amnesty' ),
			/* translators: [admin] */
			'filter_items_list'     => __( 'Filter sidebar list', 'amnesty' ),
		];

		$args = [
			/* translators: [admin] */
			'label'               => __( 'Sidebar', 'amnesty' ),
			/* translators: [admin] */
			'description'         => __( 'Sidebar post type', 'amnesty' ),
			'labels'              => $labels,
			'supports'            => [ 'title', 'editor', 'revisions' ],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 45,
			'menu_icon'           => 'dashicons-welcome-widgets-menus',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
			'rewrite'             => false,
			'codename'            => 'sidebar',
		];

		register_post_type( 'sidebar', $args );
	}
}

add_action( 'init', 'amnesty_create_sidebar_posttype', 0 );
