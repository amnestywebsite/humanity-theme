<?php

use Amnesty\Get_Image_Data;

// image attribute takes precedence over the featured image
$image_id = $attributes['imageID'];
if ( ! $image_id ) {
	$image_id = get_post_thumbnail_id();
}

$image = new Get_Image_Data( (int) $image_id );
$video = new Get_Image_Data( (int) $attributes['featuredVideoId'] );

$video_output = '';
// If the block has a featured video, get the video URL
if ( $attributes['featuredVideoId'] && 'video' === $attributes['type'] ) {
	// $video_output used in hero.php view
	$video_output .= sprintf(
		'<div class="hero-videoContainer">
			<video class="hero-video" autoplay loop muted>
				<source src="%s">
			</video>
		</div>',
		esc_url( wp_get_attachment_url( $attributes['featuredVideoId'] ) ),
	);
}

// Build output for the image/video caption and credit
// $media_meta_output used in hero.php view
// Reverse the boolean value of the arguments to match the value of the arguments in the function
$media_meta_output  = $image->metadata( ! $attributes['hideImageCaption'], ! $attributes['hideImageCopyright'], 'image' );
$media_meta_output .= $video->metadata( ! $attributes['hideImageCaption'], ! $attributes['hideImageCopyright'], 'video' );

$inner_blocks = '';
// If inner blocks are present, build the inner blocks
if ( $content ) {
	// $inner_blocks used in hero.php view
	$inner_blocks .= sprintf(
		'<div class="donation">%s</div>',
		wp_kses_post( $content )
	);
}

$alignment = '';

if ( $attributes['align'] ) {
	$alignment = 'is-aligned-' . $attributes['align'];
}

$background = 'has-dark-background';

if ( $attributes['background'] ) {
	$background = 'has-' . $attributes['background'] . '-background';
}

$has_video = '';

if ( 'video' === $attributes['type'] ) {
	$has_video = 'has-video';
}

$classname = classnames( $attributes['className'], $alignment, $background, $has_video );

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => $classname ] );

$background_image = wp_get_attachment_image_url( $image_id, 'hero-md' );

?>
<section <?php echo wp_kses_data($wrapper_attributes)?> style="aiic:ignore;background-image:url('<?php echo esc_url( $background_image ); ?>')">
	<?php echo wp_kses_post( $video_output ); ?>
	<div class="container">
		<div class="hero-contentWrapper">
			<h1>
				<span class="hero-title"><?php echo esc_html( $attributes['title'] ); ?></span>
			</h1>
			<p class="hero-content"><?php echo esc_html( $attributes['content'] ); ?></p>
			<div class="hero-cta">
				<div class="btn btn--large">
					<span><?php echo esc_html( $attributes['ctaText'] ); ?></span>
					<a href="<?php echo esc_url( $attributes['ctaLink'] ); ?>"></a>
				</div>
			</div>
		</div>
		<?php echo wp_kses_post( $inner_blocks ); ?>
	</div>
	<?php echo wp_kses_post( $media_meta_output ); ?>
</section>
