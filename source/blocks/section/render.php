<?php

declare( strict_types = 1 );

use Amnesty\Get_Image_Data;

if ( ! $attrs['sectionId'] ) {
	$attrs['sectionId'] = substr( md5( uniqid( (string) wp_rand(), true ) ), 0, 8 );
}

$image_object = new Get_Image_Data( absint( $attrs['backgroundImageId'] ) );
$show_caption = true !== amnesty_validate_boolish( $attrs['hideImageCaption'] );
$show_credit  = true !== amnesty_validate_boolish( $attrs['hideImageCopyright'] );

/* style tag output */
if ( ! $image_object->id() && $attrs['backgroundImage'] ) {
	printf(
		'<style class="aiic-ignore">#section-%s{background-image:url("%s")}</style>',
		esc_attr( $attrs['sectionId'] ),
		esc_url( $attrs['backgroundImage'] )
	);
}

$small_bg_image  = wp_get_attachment_image_url( $image_object->id(), 'hero-sm' );
$medium_bg_image = wp_get_attachment_image_url( $image_object->id(), 'hero-md' );
$large_bg_image  = wp_get_attachment_image_url( $image_object->id(), 'hero-lg' );

printf(
	( $image_object->credit() ? '<style class="aiic-ignore">' : '<style>' ) .
	'#section-%1$s{background-image:url("%2$s")}' .
	'@media screen and (min-width:770px){' .
	'#section-%1$s{background-image:url("%3$s")}' .
	'}' .
	'@media screen and (min-width:1444px){' .
	'#section-%1$s{background-image:url("%4$s")}' .
	'}' .
	'</style>',
	esc_attr( $attrs['sectionId'] ),
	esc_url( $small_bg_image ),
	esc_url( $medium_bg_image ),
	esc_url( $large_bg_image )
);
/* /style tag output */

/* section opener */
$origin  = $attrs['backgroundImageOrigin'];
$padding = $attrs['padding'];

$classes = classnames(
	'section',
	[
		'section--tinted'         => 'grey' === $attrs['background'],
		'section--textWhite'      => 'white' === $attrs['textColour'],
		'section--has-bg-image'   => (bool) $attrs['backgroundImage'],
		'section--has-bg-overlay' => (bool) $attrs['enableBackgroundGradient'],
	],
	[
		sprintf( 'section--%s', $padding )         => (bool) $padding,
		sprintf( 'section--bgOrigin-%s', $origin ) => (bool) $origin,
	],
);

$css_attr = '';

if ( $attrs['backgroundImage'] ) {
	if ( 0 === absint( $attrs['minHeight'] ) ) {
		$css_attr .= 'height:auto;';
	}

	if ( absint( $attrs['minHeight'] ) > 0 ) {
		$css_attr .= sprintf(
			'min-height:%svw;max-height:%spx;',
			absint( $attrs['minHeight'] ),
			absint( $attrs['backgroundImageHeight'] ),
		);
	}
}

?>
<section id="section-<?php echo esc_attr( $attrs['sectionId'] ); ?>" class="<?php echo esc_attr( $classes ); ?>" style="<?php echo esc_attr( $css_attr ); ?>">
	<div id="<?php echo esc_attr( $attrs['sectionId'] ); ?>" class="container">
		<?php echo wp_kses_post( $content ); ?>
	</div>
	<?php echo wp_kses_post( $image_object->metadata( $show_caption, $show_credit ) ); ?>
</section>
