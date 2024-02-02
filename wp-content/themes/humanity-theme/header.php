<?php

/**
 * Global partial, site header
 *
 * @package Amnesty\Partials
 */

$page_id   = amnesty_get_header_object_id();
$hero_data = amnesty_get_header_data( $page_id );
$hero_show = false;

$object = get_queried_object();
if ( ! is_singular( [ 'post' ] ) && ! is_search() && ! is_404() ) {
	if ( is_archive() && is_object( $object ) ) {
		$hero_data['title']   = $object->label;
		$hero_data['content'] = $object->labels->archives ?? $object->description;
	}

	$hero_show = ! empty( $hero_data['title'] ) || ! empty( $hero_data['imageID'] );
}

$body_class = [];
if ( $hero_show && ! is_singular( [ 'post' ] ) && ! is_search() && ! is_404() ) {
	$body_class[] = 'has-hero';
}

?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php echo esc_html( trim( wp_title( '|', false, 'right' ), " \t\n\r\v\0|" ) ); ?></title>
	<?php wp_head(); ?>
</head>
<body <?php body_class( $body_class ); ?>>
<?php do_action( 'amnesty_after_body_tag' ); ?>
<a class="skipLink" href="#main" tabindex="1"><?php /* translators: [front] Accessibility label for screen reader/keyboard users */ esc_html_e( 'Skip to main content', 'amnesty' ); ?></a>
<div class="overlay" aria-hidden="true"></div>
<?php

get_template_part( 'partials/pop-in' );
get_template_part( 'partials/language-selector' );
get_template_part( 'partials/navigation/desktop' );

if ( $hero_show ) {
	// phpcs:ignore
	echo \Amnesty\Blocks\amnesty_render_header_block( $hero_data );
	amnesty_remove_header_from_content();
}
