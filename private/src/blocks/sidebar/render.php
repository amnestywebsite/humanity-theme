<?php

declare( strict_types = 1 );

// the templates handle the output
if ( 'the_content' === current_filter() ) {
	return;
}

$content_maximised = amnesty_validate_boolish(
	get_post_meta( get_the_ID(), '_maximise_post_content', true ),
	false,
);

if ( $content_maximised ) {
	return;
}

$sidebar_disabled = amnesty_validate_boolish(
	get_post_meta( get_the_ID(), '_disable_sidebar', true ),
	false,
);

if ( $sidebar_disabled ) {
	if ( is_page() ) {
		return;
	}

	// empty element is intentional
	echo '<aside class="wp-block-group article-sidebar"></aside>';
	return;
}

if ( ! amnesty_is_sidebar_available() ) {
	return;
}

$sidebar = get_post( amnesty_get_sidebar_id() );

?>

<aside class="wp-block-group article-sidebar"><?php echo wp_kses_post( apply_filters( 'the_content', $sidebar->post_content ?? '' ) ); ?></aside>
