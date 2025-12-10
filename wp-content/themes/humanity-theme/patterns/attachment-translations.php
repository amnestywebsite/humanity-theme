<?php

/**
 * Title: Attachment Translations
 * Description: Output the list of translations for the entity
 * Slug: amnesty/attachment-translations
 * Inserter: no
 */

use function Amnesty\SharePoint\get_document_languages;

if ( ! function_exists( '\Amnesty\SharePoint\get_document_languages' ) ) {
	return;
}

$translations = get_document_languages( get_post() );
$translations = array_filter(
	$translations,
	function ( object $translation ): bool {
		return get_current_blog_id() !== $translation->blog_id || (
			get_current_blog_id() === $translation->blog_id &&
			get_queried_object_id() !== $translation->item_id
		);
	},
);

if ( ! count( $translations ) ) {
	return;
}

$translation_links = [];
$list_separator    = _x( ',', 'list item separator', 'amnesty' );

foreach ( $translations as $translation ) {
	$translation_links[] = sprintf(
		'<a href="%s" hreflang="%s">%s</a>',
		esc_url( get_blog_permalink( $translation->blog_id, $translation->item_id ) ),
		esc_attr( $translation->lang_iso ),
		esc_html( $translation->language ),
	);
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

		echo wp_kses_post( _x( 'Also available in', 'prefix for list of post translations', 'amnesty' ) );
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
	<summary><?php echo wp_kses_post( _x( 'Also available in', 'prefix for list of post translations', 'amnesty' ) ); ?></summary>
	<!-- wp:group -->
	<div class="wp-block-group">
		<?php echo wp_kses_post( $translation_links ); ?>
	</div>
	<!-- /wp:group -->
</details>
<!-- /wp:details -->

<?php endif; ?>
