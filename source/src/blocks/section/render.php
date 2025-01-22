<?php

declare(strict_types=1);

namespace Amnesty\Blocks;

use Amnesty\Get_Image_Data;

// Generate a unique ID for the block
$container_id = substr( md5( uniqid( (string) wp_rand(), true ) ), 0, 8 );
$section_id   = $attributes['sectionId'] ?? '';

// Get image data
$image = new Get_Image_Data( absint( $attributes['backgroundImageId'] ) );

// Prepare background image and inline style
$background_image = $attributes['backgroundImage'];

$bg_style = '';
if ( $background_image || $image->id() ) {
	if ( $image->id() ) {
		$small_bg_image  = wp_get_attachment_image_url( $image->id(), 'hero-sm' );
		$medium_bg_image = wp_get_attachment_image_url( $image->id(), 'hero-md' );
		$large_bg_image  = wp_get_attachment_image_url( $image->id(), 'hero-lg' );

		// Prepare background image as inline style for the section tag
		$bg_style = sprintf(
			'background-image: url("%1$s");' .
			'@media screen and (min-width:770px) {background-image: url("%2$s");}' .
			'@media screen and (min-width:1444px) {background-image: url("%3$s");}',
			esc_url( $small_bg_image ),
			esc_url( $medium_bg_image ),
			esc_url( $large_bg_image ),
		);
	} elseif ( $background_image ) {
		// Directly use the provided background image
		$bg_style = sprintf( 'background-image: url("%s");', esc_url( $background_image ) );
	}
}

// Prepare section attributes and classes
$origin  = $attributes['backgroundImageOrigin'];
$classes = classnames(
	'section',
	[
		'section--tinted'                          => 'grey' === ( $attributes['background'] ?? '' ),
		'section--textWhite'                       => 'white' === $attributes['textColour'],
		'section--has-bg-image'                    => (bool) $background_image,
		'section--has-bg-overlay'                  => (bool) $attributes['enableBackgroundGradient'],
		sprintf( 'section--bgOrigin-%s', $origin ) => (bool) $origin,
	]
);

// Prepare section height attributes (if applicable)
$css_attr = '';
if ( $background_image ) {
	if ( 0 === absint( $attributes['minHeight'] ) ) {
		$css_attr .= 'height:auto;';
	}
	if ( absint( $attributes['minHeight'] ) > 0 ) {
		$css_attr .= sprintf(
			'min-height:%svw;max-height:%spx;',
			absint( $attributes['minHeight'] ),
			absint( $attributes['backgroundImageHeight'] ),
		);
	}
}

// Handle image caption visibility
$hide_caption  = true === amnesty_validate_boolish( $attributes['hideImageCaption'] );
$hide_credit   = true === amnesty_validate_boolish( $attributes['hideImageCopyright'] );
$image_caption = '';
if ( $image->id() && ! ( $hide_caption && $hide_credit ) ) {
	$image_caption = wp_kses_post( $image->metadata( ! $hide_caption, ! $hide_credit ) );
}

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => $classes ] );

?>

<section id="<?php echo esc_attr( $section_id ); ?>" <?php echo wp_kses_data( $wrapper_attributes ); ?> style="<?php echo esc_attr( $css_attr . $bg_style ); ?>">
	<div id="<?php echo esc_attr( $container_id ); ?>" class="container">
		<?php echo wp_kses_post( $content ); ?>
	</div>

	<?php if ( $image_caption ) : ?>
		<div class="image-caption">
			<?php echo wp_kses_post( $image_caption ); ?>
		</div>
	<?php endif; ?>
</section>
