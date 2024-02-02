<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_get_taxonomy_labels_placeholders' ) ) {
	/**
	 * List of taxonomy labels, with placeholders for sprintf
	 *
	 * @package Amnesty
	 *
	 * @return array<string,array<string,string>>
	 */
	function amnesty_get_taxonomy_labels_placeholders(): array {
		return [
			'singular_name'              => [
				'type' => 'singular',
				'desc' => 'Name for one object of this taxonomy. Default "%s".',
			],
			'name'                       => [
				'type' => 'plural',
				'desc' => 'General name for the taxonomy, usually plural. The same as, and overridden by, `$tax->label`. Default %s.',
			],
			'menu_name'                  => [
				'type' => 'plural',
				'desc' => 'General name for the taxonomy, usually plural. The same as, and overridden by, `$tax->label`. Default %s.',
			],
			'name_admin_bar'             => [
				'type' => 'singular',
				'desc' => 'Name for one object of this taxonomy. Default "%s".',
			],
			'parent_item'                => [
				'type' => 'singular',
				'desc' => 'This label is only used for hierarchical taxonomies. Default "Parent %s".',
			],
			'parent_item_colon'          => [
				'type' => 'singular',
				'desc' => 'The same as `parent_item`, but with colon `:` in the end.',
			],
			'edit_item'                  => [
				'type' => 'singular',
				'desc' => 'Default "Edit %s".',
			],
			'view_item'                  => [
				'type' => 'singular',
				'desc' => 'Default "View %s".',
			],
			'update_item'                => [
				'type' => 'singular',
				'desc' => 'Default "Update %s".',
			],
			'add_new_item'               => [
				'type' => 'singular',
				'desc' => 'Default "Add New %s".',
			],
			'new_item_name'              => [
				'type' => 'singular',
				'desc' => 'Default "New %s Name".',
			],
			'filter_by_item'             => [
				'type' => 'singular',
				'desc' => 'This label is only used for hierarchical taxonomies. Default "Filter by %s", used in the posts list table.',
			],
			'most_used'                  => [
				'type' => 'singular',
				'desc' => 'Title for the Most Used tab. Default "Most Used".',
			],
			'item_link'                  => [
				'type' => 'singular',
				'desc' => 'Used in the block editor. Title for a navigation link block variation. Default "%s Link".',
			],
			'search_items'               => [
				'type' => 'plural',
				'desc' => 'Default "Search %s".',
			],
			'popular_items'              => [
				'type' => 'plural',
				'desc' => 'This label is only used for non-hierarchical taxonomies. Default "Popular %s".',
			],
			'all_items'                  => [
				'type' => 'plural',
				'desc' => 'Default "All %s".',
			],
			'archives'                   => [
				'type' => 'plural',
				'desc' => 'Default "All %s".',
			],
			'separate_items_with_commas' => [
				'type' => 'plural',
				'desc' => 'This label is only used for non-hierarchical taxonomies. Default "Separate %s with commas", used in the meta box.',
			],
			'add_or_remove_items'        => [
				'type' => 'plural',
				'desc' => 'This label is only used for non-hierarchical taxonomies. Default "Add or remove %s", used in the meta box when JavaScript is disabled.',
			],
			'choose_from_most_used'      => [
				'type' => 'plural',
				'desc' => 'This label is only used on non-hierarchical taxonomies. Default "Choose from the most used %s", used in the meta box.',
			],
			'not_found'                  => [
				'type' => 'plural',
				'desc' => 'Default "No %s found", used in the meta box and taxonomy list table.',
			],
			'no_terms'                   => [
				'type' => 'plural',
				'desc' => 'Default "No %s", used in the posts and media list tables.',
			],
			'items_list_navigation'      => [
				'type' => 'plural',
				'desc' => 'Label for the table pagination hidden heading.',
			],
			'items_list'                 => [
				'type' => 'plural',
				'desc' => 'Label for the table hidden heading.',
			],
			'back_to_items'              => [
				'type' => 'plural',
				'desc' => 'Label displayed after a term has been updated.',
			],
		];
	}
}
