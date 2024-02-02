<?php

/**
 * Taxonomy partial, hero
 *
 * @package Amnesty\Partials
 */

use Amnesty\Get_Image_Data;

$object   = get_queried_object();
$term_id  = get_queried_object_id();
$image_id = absint( get_term_meta( $term_id, 'image_id', true ) );

// if the term is a report with no image, grab its parent term image instead
if ( ! $image_id && 'report' === get_term_meta( $term_id, 'type', true ) ) {
	$image_id = absint( get_term_meta( get_term_parent( $object )->term_id, 'image_id', true ) );
}

$media_lg = wp_get_attachment_image_url( $image_id, 'hero-lg' );
$media_md = wp_get_attachment_image_url( $image_id, 'hero-md' );
$media_sm = wp_get_attachment_image_url( $image_id, 'hero-sm' );
$image    = new Get_Image_Data( $image_id );
$hero_id  = substr( md5( uniqid( wp_rand(), true ) ), 0, 8 );

if ( $media_lg ) {
	$selector = sprintf( '#hero-%s', $hero_id );
	printf(
		'<style>%s%s%s</style>',
		sprintf( '%s{background-image:url("%s")}', esc_attr( $selector ), esc_url( $media_sm ) ),
		sprintf( '@media screen and (min-width:770px){%s{background-image:url("%s")}}', esc_attr( $selector ), esc_url( $media_md ) ),
		sprintf( '@media screen and (min-width:1444px){%s{background-image:url("%s")}}', esc_attr( $selector ), esc_url( $media_lg ) )
	);
}

?>
<section id="<?php echo esc_attr( sprintf( 'hero-%s', $hero_id ) ); ?>" class="page-hero page-heroSize--small page-heroAlignment--left page-heroBackground--dark" role="region" aria-labelledby="herotitle">
	<div class="container">
		<div class="hero-content">
			<h1 id="herotitle" class="page-heroTitle"><span><?php echo wp_kses_post( apply_filters( 'the_title', $object->name ) ); ?></span></h1>
		</div>
	</div>
<?php echo wp_kses_post( $image->metadata() ); ?>
</section>
