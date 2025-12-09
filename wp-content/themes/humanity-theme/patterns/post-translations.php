<?php

/**
 * Title: Post Translations
 * Description: Output the list of translations for the entity
 * Slug: amnesty/post-translations
 * Inserter: no
 */

$translations = get_object_translations( filter: false );

if ( ! count( $translations ) ) {
	return;
}

$translation_links = [];
$list_separator    = _x( ',', 'list item separator', 'amnesty' );

foreach ( $translations as $translation ) {
	if ( ! $translation->remoteUrl() ) {
		continue;
	}

	$translation_links[] = sprintf(
		'<a href="%s" hreflang="%s">%s</a>',
		esc_url( $translation->remoteUrl() ),
		esc_attr( $translation->language()->bcp47tag() ),
		esc_html( $translation->language()->name() ),
	);
}

// if there's only one link, it's the current language
if ( count( $translation_links ) < 2 ) {
	return;
}

$has_few = count( $translation_links ) < 7;

if ( $has_few ) {
	$translation_links = implode( $list_separator, $translation_links );
} else {
	$translation_links = array_map(
		function ( string $link ): string {
			return sprintf(
				"<!-- wp:paragraph -->\n<p>%s</p>\n<!-- /wp:paragraph -->",
				$link,
			);
		},
		$translation_links,
	);

	$translation_links = implode( "\n", $translation_links );
}

// AI requirements
if ( $has_few ) :

	?>

	<!-- wp:paragraph -->
	<p>
		<?php

		echo wp_kses_post( _x( 'Available in', 'prefix for list of post translations', 'amnesty' ) );
		echo '&nbsp;';
		echo wp_kses_post( $translation_links );

		?>
	</p>
	<!-- /wp:paragraph -->

	<?php

else :


	?>

<!-- wp:details {"className":"is-style-small"} -->
<details class="wp-block-details is-style-small">
	<summary><?php echo wp_kses_post( _x( 'Available in', 'prefix for list of post translations', 'amnesty' ) ); ?></summary>
	<!-- wp:group -->
	<div class="wp-block-group">
		<?php echo wp_kses_post( $translation_links ); ?>
	</div>
	<!-- /wp:group -->
</details>
<!-- /wp:details -->

<?php endif; ?>
