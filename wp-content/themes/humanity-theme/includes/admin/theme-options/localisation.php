<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_localisation_options' ) ) {
	/**
	 * Register settings with CMB2 for localisation
	 *
	 * @package Amnesty\ThemeOptions
	 *
	 * @return void
	 */
	function amnesty_register_localisation_options(): void {
		$localisation = new_cmb2_box(
			[
				'id'           => 'amnesty_localisation_options_page',
				'option_key'   => 'amnesty_localisation_options_page',
				/* translators: [admin] */
				'title'        => __( 'Localisation', 'amnesty' ),
				'object_types' => [ 'options-page' ],
				'tab_group'    => 'amnesty_theme_options',
				/* translators: [admin] */
				'tab_title'    => __( 'Localisation', 'amnesty' ),
				'parent_slug'  => 'amnesty_theme_options_page',
				'display_cb'   => 'amnesty_options_display_with_tabs',
			] 
		);

		$localisation->add_field(
			[
				/* translators: [admin] */
				'name'             => __( 'Ordered List Character Choice', 'amnesty' ),
				'id'               => 'ol_locale_option',
				'type'             => 'select',
				/* translators: [admin] */
				'default'          => __( 'Please select', 'amnesty' ),
				'show_option_none' => false,
				'options'          => [
					/* translators: [admin] */
					''                      => __( 'Default; Western Arabic numerals', 'amnesty' ),
					/* translators: [admin] */
					'arabic-indic'          => __( 'Arabic-Indic numbers', 'amnesty' ),
					/* translators: [admin] */
					'bengali'               => __( 'Bengali numbering', 'amnesty' ),
					/* translators: [admin] */
					'cambodian-khmer'       => __( 'Cambodian/Khmer numbering', 'amnesty' ),
					/* translators: [admin] */
					'devanagari'            => __( 'Devanagari numbering', 'amnesty' ),
					/* translators: [admin] */
					'ethiopic-numeric'      => __( 'Ethiopic numbering', 'amnesty' ),
					/* translators: [admin] */
					'korean-hanja-formal'   => __( 'Formal Korean Han numbering', 'amnesty' ),
					/* translators: [admin] */
					'gujarati'              => __( 'Gujarati numbering', 'amnesty' ),
					/* translators: [admin] */
					'gurmukhi'              => __( 'Gurmukhi numbering', 'amnesty' ),
					/* translators: [admin] */
					'cjk-earthly-branch'    => __( 'Han "Earthly Branch" ordinals', 'amnesty' ),
					/* translators: [admin] */
					'cjk-heavenly-stem'     => __( 'Han "Heavenly Stem" ordinals', 'amnesty' ),
					/* translators: [admin] */
					'japanese-formal'       => __( 'Japanese formal numbering', 'amnesty' ),
					/* translators: [admin] */
					'japanese-informal'     => __( 'Japanese informal numbering', 'amnesty' ),
					/* translators: [admin] */
					'kannada'               => __( 'Kannada numbering', 'amnesty' ),
					/* translators: [admin] */
					'korean-hangul-formal'  => __( 'Korean hangul numbering', 'amnesty' ),
					/* translators: [admin] */
					'korean-hanja-informal' => __( 'Korean hanja numbering', 'amnesty' ),
					/* translators: [admin] */
					'lao'                   => __( 'Laotian numbering', 'amnesty' ),
					/* translators: [admin] */
					'lower-armenian'        => __( 'Lowercase Armenian numbering', 'amnesty' ),
					/* translators: [admin] */
					'lower-latin'           => __( 'Lowercase ASCII letters', 'amnesty' ),
					/* translators: [admin] */
					'lower-greek'           => __( 'Lowercase classical Greek.', 'amnesty' ),
					/* translators: [admin] */
					'lower-roman'           => __( 'Lowercase Roman numerals.', 'amnesty' ),
					/* translators: [admin] */
					'malayalam'             => __( 'Malayalam numbering', 'amnesty' ),
					/* translators: [admin] */
					'mongolian'             => __( 'Mongolian numbering', 'amnesty' ),
					/* translators: [admin] */
					'myanmar'               => __( 'Myanmar (Burmese) numbering', 'amnesty' ),
					/* translators: [admin] */
					'oriya'                 => __( 'Oriya numbering', 'amnesty' ),
					/* translators: [admin] */
					'persian'               => __( 'Persian numbering', 'amnesty' ),
					/* translators: [admin] */
					'simp-chinese-formal'   => __( 'Simplified Chinese formal numbering', 'amnesty' ),
					/* translators: [admin] */
					'simp-chinese-informal' => __( 'Simplified Chinese informal numbering', 'amnesty' ),
					/* translators: [admin] */
					'tamil'                 => __( 'Tamil numbering', 'amnesty' ),
					/* translators: [admin] */
					'telugu'                => __( 'Telugu numbering', 'amnesty' ),
					/* translators: [admin] */
					'thai'                  => __( 'Thai numbering', 'amnesty' ),
					/* translators: [admin] */
					'tibetan'               => __( 'Tibetan numbering', 'amnesty' ),
					/* translators: [admin] */
					'armenian'              => __( 'Traditional Armenian numbering', 'amnesty' ),
					/* translators: [admin] */
					'trad-chinese-formal'   => __( 'Traditional Chinese formal numbering', 'amnesty' ),
					/* translators: [admin] */
					'trad-chinese-informal' => __( 'Traditional Chinese informal numbering', 'amnesty' ),
					/* translators: [admin] */
					'georgian'              => __( 'Traditional Georgian numbering', 'amnesty' ),
					/* translators: [admin] */
					'hebrew'                => __( 'Traditional Hebrew numbering', 'amnesty' ),
					/* translators: [admin] */
					'upper-armenian'        => __( 'Traditional uppercase Armenian numbering', 'amnesty' ),
					/* translators: [admin] */
					'upper-latin'           => __( 'Uppercase ASCII letters', 'amnesty' ),
					/* translators: [admin] */
					'upper-roman'           => __( 'Uppercase Roman numerals.', 'amnesty' ),
				],
			] 
		);

		do_action( 'amnesty_register_localisation_options', $localisation );
	}
}

add_action( 'amnesty_register_theme_options', 'amnesty_register_localisation_options' );
