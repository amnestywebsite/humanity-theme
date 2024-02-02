<?php

declare( strict_types = 1 );

namespace Amnesty;

use CMB2;

new Taxonomy_Labels();

/**
 * Allow taxonomy labels to be content managed
 *
 * @package Amnesty\ThemeOptions
 */
class Taxonomy_Labels {

	/**
	 * Bind hooks
	 */
	public function __construct() {
		add_action( 'amnesty_register_localisation_options', [ $this, 'register' ] );
	}

	/**
	 * Register taxonomy labels with theme localisation settings
	 *
	 * Allows taxonomies to be renamed in a way that also allows
	 * them to be translated.
	 *
	 * @param \CMB2 $localisation the localisation CMB2 object
	 *
	 * @return void
	 */
	public function register( CMB2 $localisation ): void {
		$taxonomies = get_taxonomies(
			[
				'public'  => true,
				'amnesty' => true,
			] 
		);

		foreach ( $taxonomies as $tax_slug ) {
			$this->add_fields_for_taxonomy( $tax_slug, $localisation );
		}
	}

	/**
	 * Register label options for a taxonomy
	 *
	 * @param string $tax_slug     the taxonomy slug (dynamic)
	 * @param \CMB2  $localisation the localisation CMB2 object
	 *
	 * @return void
	 */
	protected function add_fields_for_taxonomy( string $tax_slug, CMB2 $localisation ): void {
		$taxonomy  = get_taxonomy( $tax_slug );
		$classname = $taxonomy->classname;

		$all_labels = amnesty_get_taxonomy_labels_placeholders();

		$default_labels = $classname::labels( true );
		$config_labels  = get_taxonomy_labels( $taxonomy );

		$group = $localisation->add_field(
			[
				'id'         => $tax_slug . '_labels',
				'name'       => sprintf( '"%s" Taxonomy Labels', $default_labels->name ),
				'type'       => 'group',
				'classes'    => 'ai-cmb-group-title-wide',
				'repeatable' => false,
				'options'    => [
					'sortable' => false,
					'closed'   => true,
				],
			] 
		);

		foreach ( $all_labels as $key => $label ) {
			$this->add_field_for_taxonomy_label(
				$key,
				$label,
				[
					'localisation'   => $localisation,
					'group'          => $group,
					'tax_slug'       => $tax_slug,
					'default_labels' => $default_labels,
					'config_labels'  => $config_labels,
				] 
			);
		}
	}

	/**
	 * Register a label option for a taxonomy
	 *
	 * @param string              $key   the key of the label
	 * @param array               $label the label data
	 * @param array<string,mixed> $data  the taxonomy data
	 *
	 * @return void
	 */
	protected function add_field_for_taxonomy_label( string $key, array $label, array $data ): void {
		// we need either a default or a configured label, but not neither
		if ( ! isset( $data['default_labels']->{$key} ) && ! isset( $data['config_labels']->{$key} ) ) {
			return;
		}

		$type = $label['type'];
		$desc = $label['desc'];
		$name = null;

		switch ( $type ) {
			case 'singular':
				$name = $data['default_labels']->singular_name ?? $data['config_labels']->singular_name ?? null;
				break;
			case 'plural':
				$name = $data['default_labels']->name ?? $data['config_labels']->name ?? null;
				break;
			default:
				return;
		}

		$data['localisation']->add_group_field(
			$data['group'],
			[
				'id'          => $data['tax_slug'] . '_label_' . $key,
				'name'        => sprintf( '"%s" label', $key ),
				'type'        => 'text',
				'description' => sprintf( $desc, $name ),
				'default'     => $data['default_labels']->{$key} ?? '',
			] 
		);
	}

}
